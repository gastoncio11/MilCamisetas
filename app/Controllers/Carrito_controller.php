<?php
namespace App\Controllers;

use App\Models\Libro_Model;
use App\Models\Categoria_Model;

class Carrito_controller extends BaseController {

    public function ver_carrito()
    {
        $cart = \Config\Services::cart();
        $data['titulo'] = 'Carrito de compras';
        
        return view('plantillas/encabezado', $data)
             . view('plantillas/nav')
             . view('contenidos/carrito_view')
             . view('plantillas/footer');
    }

    public function agregar_carrito()
    {
        $cart = \Config\Services::cart();
        $request = \Config\Services::request();
        
        $data = array(
            'id' => $request->getPost('id'),
            'name' => $request->getPost('titulo'),
            'price' => $request->getPost('precio'),
            'qty' => 1
        );
        
        $cart->insert($data);
        
        // Mensaje que se agregó al carrito
        return redirect()->route('ver_carrito');
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

    public function guardar_venta() {
        $cart = \Config\Services::cart();
        $venta = new Venta_Model();
        $detalle = new Detalle_Venta_Model();
        $libros = new Libro_Model();

        $cart1 = $cart->contents();

        foreach ($cart1 as $item) {
            $libro = $libros->where('libro_id', $item['id'])->first();
            if ($libro['libro_stock'] < $item['qty']) {
                return redirect()->route('ver_carrito');
            }
        }

        $data = array(
            'id_cliente' => session('id'),
            'venta_fecha' => date('Y-m-d'),
        );
        $venta_id = $venta->insert($data);

        foreach ($cart1 as $item) {
            $detalle_venta = array(
                'id_venta' => $venta_id,
                'id_producto' => $item['id'],
                'detalle_cantidad' => $item['qty'],
                'detalle_precio' => $item['price']
            );
            
            $libro = $libros->where('libro_id', $item['id'])->first();
            $data = [
                'libro_stock' => $libro['libro_stock'] - $item['qty'],
            ];
            $libros->update($item['id'], $data);

            $detalle->insert($detalle_venta);
        }

        $cart->destroy();
        return redirect()->route('productos');
    }

}