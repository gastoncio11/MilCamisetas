<?php
namespace App\Controllers;

use App\Models\Producto_model;

class Producto_controller extends BaseController
{
    public function catalogo()
    {
        $model = new \App\Models\Producto_model();
        $productos = $model->where('activo', 1)->findAll();

        $db = \Config\Database::connect();
        foreach ($productos as &$p) {
            $talles = $db->query("SELECT talle FROM producto_talle WHERE producto_id = ?", [$p['id']])->getResultArray();
            $p['talles'] = array_column($talles, 'talle');
        }

        $data = [
            'title' => 'Catálogo | MilCamisetas',
            'productos' => $productos
        ];

        return view('plantillas/header', $data)
            . view('catalogo', $data)
            . view('plantillas/footer');
    }

    public function producto($id)
    {
        $model = new \App\Models\Producto_model();
        $producto = $model->find($id);

        if (!$producto) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Producto no encontrado");
        }

        $db = \Config\Database::connect();
        $talles = $db->query("SELECT talle FROM producto_talle WHERE producto_id = ?", [$id])->getResultArray();
        $producto['talles'] = array_column($talles, 'talle');

        $data = [
            'title' => $producto['nombre'] . ' | MilCamisetas',
            'producto' => $producto
        ];

        return view('plantillas/header', $data)
            . view('producto', $data)
            . view('plantillas/footer');
    }
}
