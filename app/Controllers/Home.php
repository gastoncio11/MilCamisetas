<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
    return view('plantillas/header').
    view('Views/principal').
     view('plantillas/footer');
    }

    public function nosotros(){
        return view('plantillas/header').
        view('Views/nosotros').
        view('plantillas/footer');
    }

    public function contacto(){
        return view('plantillas/header').
        view('Views/contacto').
        view('plantillas/footer');
    }

    public function principal(){
        return view('plantillas/header').
        view('Views/principal').
        view('plantillas/footer');
    }

    public function terminosdeuso(){
        return view('plantillas/header').
        view('Views/terminosdeuso').
        view('plantillas/footer');
    }

    public function comercio(){
        return view('plantillas/header').
        view('Views/comercio').
        view('plantillas/footer');
    }

     public function login(){
        return view('plantillas/header').
        view('Views/login').
        view('plantillas/footer');
    }

    public function registro(){
        return view('plantillas/header').
        view('Views/registro').
        view('plantillas/footer');
    }



}
