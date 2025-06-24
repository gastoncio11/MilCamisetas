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

public function bien(){
    $data['title'] = 'brisa';
    return view('plantillas/header', $data).
           view('bien').
           view('plantillas/footer');
}

public function operaciones()
    {
        $data['title'] = 'Operaciones';

        return view('plantillas/header', $data).
               view('operaciones').
               view('plantillas/footer');
    }



}
