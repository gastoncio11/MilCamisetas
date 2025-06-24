<?php
namespace App\Controllers;

use App\Models\Libro_Model;
use App\Models\Categoria_Model;

class Carrito_controller extends BaseController {

    public function ver_carrito()
    {
        $cart = \Config\Services::cart();
        $data['titulo'] = 'Carrito de compras';
        
        return view('plantillas/header', $data)
             . view('carrito')
             . view('plantillas/footer');
    }

    public function agregar_carrito()
    {
        $cart = \Config\Services::cart();
        $request = \Config\Services::request();

        $producto_id = $request->getPost('id');
        $producto_nombre = $request->getPost('titulo');
        $precio = $request->getPost('precio');
        $talle = $request->getPost('talle');
        $cantidad = $request->getPost('cantidad');

        $data = [
            'id'      => $producto_id,
            'name'    => $producto_nombre . ' - Talle ' . $talle,
            'price'   => $precio,
            'qty'     => $cantidad
        ];

        $cart->insert($data);

        return redirect()->route('carrito');
    }

    public function eliminar_item($rowid) 
    {
        $cart = \Config\Services::cart();
        $cart->remove($rowid);
        return redirect()->back();
    }

    public function vaciar_carrito()
    {
        $cart = \Config\Services::cart();
        $cart->destroy();
        return redirect()->back();
    }

    public function guardar_venta()
    {
        $cart = \Config\Services::cart();
        $items = $cart->contents();

        if (empty($items)) {
            return redirect()->to('carrito')->with('error', 'El carrito está vacío.');
        }

        // Verificar sesión
        $usuario_id = session()->get('usuario_id');
        if (!$usuario_id) {
            return redirect()->to('login')->with('error', 'Debes iniciar sesión para comprar.');
        }

        $ventaModel = new \App\Models\Venta_model();
        $detalleModel = new \App\Models\Detalle_venta_model();
        $stockModel = new \App\Models\ProductoStock_model();
        $db = \Config\Database::connect();

        $db->transStart();

        // Calcular total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // Crear venta
        $ventaData = [
            'id_usuario' => $usuario_id,
            'fecha' => date('Y-m-d H:i:s'),
            'total' => $total
        ];
        $venta_id = $ventaModel->insert($ventaData);

        foreach ($items as $item) {
            // Extraer talle desde el nombre del producto
            preg_match('/Talle (\w+)/', $item['name'], $match);
            $talle = $match[1] ?? 'M';

            // Obtener stock actual desde producto_stock
            $stock = $stockModel->where('producto_id', $item['id'])
                                ->where('talle', $talle)
                                ->first();

            if (!$stock || $stock['stock'] < $item['qty']) {
                $cart->remove($item['rowid']);
                return redirect()->to('carrito')->with('error', 'Stock no disponible para "' . $item['name'] . '".');
            }

            // Insertar detalle de venta
            $detalleModel->insert([
                'id_venta' => $venta_id,
                'producto_id' => $item['id'],
                'talle' => $talle,
                'cantidad' => $item['qty'],
                'precio_unitario' => $item['price']
            ]);

            // Actualizar stock
            $stockModel->actualizarStock($item['id'], $talle, $stock['stock'] - $item['qty']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('carrito')->with('error', 'Error al procesar la compra.');
        }

        $cart->destroy();
            return redirect()->to('carrito')->with('success', '✅ Tu compra ha sido realizada con éxito. Tus artículos llegarán en menos de 24hs a la dirección de tu casa. ¡Muchas gracias!');
        }




}