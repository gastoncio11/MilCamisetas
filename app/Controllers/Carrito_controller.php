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

        $stockModel = new \App\Models\ProductoTalle_model();
        $stock = $stockModel->where('producto_id', $producto_id)
                            ->where('talle', $talle)
                            ->first();

        if (!$stock) {
            return redirect()->to('producto/' . $producto_id)
                ->with('error', 'No se encontró el talle seleccionado para este producto.');
        }

        if ($stock['stock'] < $cantidad) {
            return redirect()->to('producto/' . $producto_id)
                ->with('error', 'No hay suficiente stock disponible para ese talle.');
        }

        $data = [
            'id'      => $producto_id,
            'name'    => $producto_nombre,
            'price'   => $precio,
            'qty'     => $cantidad,
            'options' => [
                'talle' => $talle
            ]
        ];

        $cart->insert($data);
        return redirect()->to(base_url('carrito'))->with('success', 'Producto agregado al carrito');
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

        // OBTENER MÉTODO DE PAGO
        $metodo_pago = $this->request->getPost('metodo_pago') ?? 'tarjeta';

        // VALIDAR DATOS BÁSICOS
        $validation = \Config\Services::validation();

        // REGLAS BASE PARA TODOS LOS MÉTODOS
        $rules = [
            'localidad' => 'required|min_length[2]|max_length[255]',
            'telefono' => 'required|min_length[8]|max_length[20]',
            'metodo_pago' => 'required|in_list[efectivo,tarjeta,mercadopago]'
        ];

        // SOLO AGREGAR VALIDACIONES DE TARJETA SI EL MÉTODO ES TARJETA
        if ($metodo_pago === 'tarjeta') {
            $rules['numero_tarjeta'] = 'required|min_length[16]|max_length[19]';
            $rules['cvv'] = 'required|exact_length[3]|numeric';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = implode(', ', $validation->getErrors());
            return redirect()->to('carrito')->with('error', 'Error en los datos: ' . $errors);
        }

        // OBTENER DATOS DEL FORMULARIO SOLO SI SON NECESARIOS
        $localidad = $this->request->getPost('localidad');
        $telefono = $this->request->getPost('telefono');

        // SOLO OBTENER DATOS DE TARJETA SI EL MÉTODO ES TARJETA
        $numero_tarjeta = null;
        $cvv = null;

        if ($metodo_pago === 'tarjeta') {
            $numero_tarjeta = str_replace(' ', '', $this->request->getPost('numero_tarjeta'));
            $cvv = $this->request->getPost('cvv');
        }

        $ventaModel = new \App\Models\Venta_model();
        $detalleModel = new \App\Models\Detalle_venta_model();
        $datosEnvioModel = new \App\Models\DatosEnvio_model();
        $stockModel = new \App\Models\ProductoTalle_model();
        $db = \Config\Database::connect();

        $db->transStart();

        try {
            // Calcular total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['price'] * $item['qty'];
            }

            // Crear venta CON MÉTODO DE PAGO
            $venta_id = $ventaModel->insert([
                'id_usuario' => $usuario_id,
                'fecha' => date('Y-m-d H:i:s'),
                'total' => $total,
                'metodo_pago' => $metodo_pago
            ]);

            // Guardar datos de envío y pago
            $datosEnvio = [
                'id_venta' => $venta_id,
                'localidad' => $localidad,
                'telefono' => $telefono
            ];

            // Solo agregar datos de tarjeta si el método es tarjeta
            if ($metodo_pago === 'tarjeta') {
                $datosEnvio['numero_tarjeta'] = $numero_tarjeta; // En producción, esto debería estar encriptado
                $datosEnvio['cvv'] = $cvv; // En producción, esto NO debería guardarse
            }

            $datosEnvioModel->insert($datosEnvio);

            // Procesar items del carrito
            foreach ($items as $item) {
                $talle = $item['options']['talle'] ?? null;

                if (!$talle) {
                    throw new \RuntimeException('Error: el producto "' . $item['name'] . '" no tiene talle seleccionado.');
                }

                // Verificar stock actual
                $stock = $stockModel->where('producto_id', $item['id'])
                                    ->where('talle', $talle)
                                    ->first();

                if (!$stock || $stock['stock'] < $item['qty']) {
                    throw new \RuntimeException('Stock no disponible para "' . $item['name'] . '" (talle ' . $talle . ').');
                }

                // Insertar detalle de venta
                $detalleModel->insert([
                    'id_venta' => $venta_id,
                    'producto_id' => $item['id'],
                    'talle' => $talle,
                    'cantidad' => $item['qty'],
                    'precio_unitario' => $item['price']
                ]);

                // Restar stock
                $stockModel->restarStock($item['id'], $talle, $item['qty']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Error en la transacción de base de datos.');
            }

            // Limpiar carrito
            $cart->destroy();

            // MENSAJES ESPECÍFICOS SEGÚN MÉTODO DE PAGO
            $mensaje_exito = $this->getMensajeExito($metodo_pago, $localidad, $telefono, $total);

            return redirect()->to('carrito')->with('success', $mensaje_exito);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('carrito')->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
    }

    private function getMensajeExito($metodo_pago, $localidad, $telefono, $total)
    {
        $total_formateado = '$' . number_format($total, 2);
        
        switch ($metodo_pago) {
            case 'efectivo':
                return '🎉 ¡Tu pedido ha sido registrado con éxito! ' .
                       'Total a pagar en efectivo: ' . $total_formateado . '. ' .
                       'Tus artículos llegarán a ' . $localidad . ' en menos de 24hs. ' .
                       'Te contactaremos al ' . $telefono . ' para coordinar la entrega y el pago. ' .
                       'También recibirás un correo con las instrucciones detalladas. ¡Muchas gracias!';
                       
            case 'mercadopago':
                return '🚀 ¡Tu compra con MercadoPago ha sido procesada exitosamente! ' .
                       'Total pagado: ' . $total_formateado . '. ' .
                       'Tus artículos llegarán a ' . $localidad . ' en menos de 24hs. ' .
                       'Te contactaremos al ' . $telefono . ' para coordinar la entrega. ' .
                       'Recibirás un correo con el comprobante de pago. ¡Muchas gracias!';
                       
            default: // tarjeta
                return '🎉 ¡Tu compra ha sido realizada con éxito! ' .
                       'Total pagado: ' . $total_formateado . '. ' .
                       'Tus artículos llegarán a ' . $localidad . ' en menos de 24hs. ' .
                       'Te contactaremos al ' . $telefono . ' para coordinar la entrega. ¡Muchas gracias!';
        }
    }
}
