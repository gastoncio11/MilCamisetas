<?php
namespace App\Controllers;

use App\Models\Producto_model;
use App\Models\Categoria_model;
use App\Models\ProductoTalle_model;

class Producto_controller extends BaseController
{
    public function catalogo()
    {
        $categoriaId = $this->request->getGet('categoria');
        $productoModel = new Producto_model();
        $categoriaModel = new Categoria_model();

        // Obtener todas las categorías
        $categorias = $categoriaModel->findAll();

        // Obtener productos según la categoría seleccionada
        if ($categoriaId) {
            $productos = $productoModel->where('activo', 1)->where('id_categoria', $categoriaId)->findAll();
        } else {
            $productos = $productoModel->where('activo', 1)->findAll();
        }

        // Usar ProductoTalle_model (la tabla que realmente existe)
        $stockModel = new ProductoTalle_model();
        foreach ($productos as &$p) {
            $stock_detalle = $stockModel->getStockPorProducto($p['id']);
            $p['talles'] = array_column($stock_detalle, 'talle');
            $p['stock_detalle'] = $stock_detalle;
            
            // Calcular stock total para mostrar en catálogo
            $p['stock_total_real'] = array_sum(array_column($stock_detalle, 'stock'));
            $p['tiene_stock'] = $p['stock_total_real'] > 0;
        }

        $data = [
            'title' => 'Catálogo | MilCamisetas',
            'productos' => $productos,
            'categorias' => $categorias,
            'categoriaSeleccionada' => $categoriaId
        ];
        
        return view('plantillas/header', $data)
            . view('catalogo', $data)
            . view('plantillas/footer');
    }

    public function producto($id)
    {
        $model = new Producto_model();
        $producto = $model->find($id);

        if (!$producto || $producto['activo'] != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Producto no encontrado o no disponible");
        }

        // Usar ProductoTalle_model (la tabla que realmente existe)
        $stockModel = new ProductoTalle_model();
        $stock_detalle = $stockModel->getStockPorProducto($id);
        $producto['talles'] = array_column($stock_detalle, 'talle');
        $producto['stock_detalle'] = $stock_detalle;
        
        // Calcular stock total
        $producto['stock_total_real'] = array_sum(array_column($stock_detalle, 'stock'));
        $producto['tiene_stock'] = $producto['stock_total_real'] > 0;

        $data = [
            'title' => $producto['nombre'] . ' | MilCamisetas',
            'producto' => $producto
        ];

        return view('plantillas/header', $data)
            . view('producto', $data)
            . view('plantillas/footer');
    }

    // Para verificar stock disponible (útil para AJAX)
    public function verificarStock()
    {
        $producto_id = $this->request->getPost('producto_id');
        $talle = $this->request->getPost('talle');
        
        if (!$producto_id || !$talle) {
            return $this->response->setJSON(['error' => 'Datos incompletos']);
        }
        
        $stockModel = new ProductoTalle_model();
        $stock = $stockModel->where('producto_id', $producto_id)
                           ->where('talle', $talle)
                           ->first();
        
        if ($stock) {
            return $this->response->setJSON([
                'success' => true,
                'stock_disponible' => $stock['stock'],
                'disponible' => $stock['stock'] > 0
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'stock_disponible' => 0,
                'disponible' => false
            ]);
        }
    }

    // Para buscar productos
    public function buscar()
    {
        $termino = $this->request->getGet('q');
        
        if (empty($termino)) {
            return redirect()->to('catalogo');
        }
        
        $productoModel = new Producto_model();
        $categoriaModel = new Categoria_model();
        
        // Buscar productos por nombre o descripción
        $productos = $productoModel->like('nombre', $termino)
                                  ->orLike('descripcion', $termino)
                                  ->where('activo', 1)
                                  ->findAll();
        
        // Agregar stock a cada producto
        $stockModel = new ProductoTalle_model();
        foreach ($productos as &$p) {
            $stock_detalle = $stockModel->getStockPorProducto($p['id']);
            $p['talles'] = array_column($stock_detalle, 'talle');
            $p['stock_detalle'] = $stock_detalle;
            $p['stock_total_real'] = array_sum(array_column($stock_detalle, 'stock'));
            $p['tiene_stock'] = $p['stock_total_real'] > 0;
        }
        
        $categorias = $categoriaModel->findAll();
        
        $data = [
            'title' => 'Búsqueda: ' . $termino . ' | MilCamisetas',
            'productos' => $productos,
            'categorias' => $categorias,
            'termino_busqueda' => $termino,
            'categoriaSeleccionada' => null
        ];
        
        return view('plantillas/header', $data)
            . view('catalogo', $data)
            . view('plantillas/footer');
    }
}
