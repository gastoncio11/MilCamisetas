<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
{
    $data['title'] = 'Inicio | MilCamisetas';
    return view('plantillas/header', $data).
           view('principal').
           view('plantillas/footer');
}

public function nosotros(){
    $data['title'] = 'Nosotros | MilCamisetas';
    return view('plantillas/header', $data).
           view('nosotros').
           view('plantillas/footer');
}

public function contacto(){
    $data['title'] = 'Contacto | MilCamisetas';
    return view('plantillas/header', $data).
           view('contacto').
           view('plantillas/footer');
}

public function principal(){
    $data['title'] = 'Principal | MilCamisetas';
    return view('plantillas/header', $data).
           view('principal').
           view('plantillas/footer');
}

public function terminosdeuso(){
    $data['title'] = 'Términos de Uso | MilCamisetas';
    return view('plantillas/header', $data).
           view('terminosdeuso').
           view('plantillas/footer');
}

public function comercio(){
    $data['title'] = 'Comercio | MilCamisetas';
    return view('plantillas/header', $data).
           view('comercio').
           view('plantillas/footer');
}

public function catalogo() {
    $db = \Config\Database::connect();
    $productos = $db->query("SELECT * FROM productos WHERE activo = 1")->getResultArray();

    foreach ($productos as &$p) {
        $id = $p['id'];
        $talles = $db->query("SELECT talle FROM producto_talle WHERE producto_id = $id AND stock > 0")->getResultArray();
        $p['talles'] = array_column($talles, 'talle');
    }

    $data['title'] = 'Catálogo | MilCamisetas';
    $data['productos'] = $productos;

    return view('plantillas/header', $data)
         . view('catalogo', $data)
         . view('plantillas/footer');
}

public function producto($id) {
    $db = \Config\Database::connect();
    $producto = $db->query("SELECT * FROM productos WHERE id = $id AND activo = 1")->getRowArray();

    if (!$producto) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $talles = $db->query("SELECT talle FROM producto_talle WHERE producto_id = $id AND stock > 0")->getResultArray();
    $producto['talles'] = array_column($talles, 'talle');

    $data['title'] = $producto['nombre'] . ' | MilCamisetas';
    $data['producto'] = $producto;

    return view('plantillas/header', $data)
         . view('producto', $data)
         . view('plantillas/footer');
}

public function login(){
    $data['title'] = 'Iniciar sesión | MilCamisetas';
    return view('plantillas/header', $data).
           view('login').
           view('plantillas/footer');
}

public function registro(){
    $data['title'] = 'Registro | MilCamisetas';
    return view('plantillas/header', $data).
           view('registro').
           view('plantillas/footer');
}

}
