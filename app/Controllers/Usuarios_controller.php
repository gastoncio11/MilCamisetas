<?php

namespace App\Controllers;

use App\Models\Consulta_model;
use App\Models\Usuarios_model;

class Usuarios_controller extends BaseController
{
    // En el controlador Usuarios_controller.php: Crear la siguiente función:
public function add_cliente() {
    $validation = \Config\Services::validation();
    $request = \Config\Services::request();

    $validation->setRules([
        'nombre' => 'required',
        'apellido' => 'required',
        'direccion' => 'required',
        'correo' => 'required|valid_email|is_unique[personas.persona_mail]',
        'pass' => 'required|min_length[8]',
        'repass' => 'required|min_length[8]|matches[pass]'
    ], [
        // Errores
        // Completar los mensajes de las reglas de validación
        'correo' => [
            'required' => 'El correo es obligatorio',
            'is_unique' => 'El cliente ya se encuentra registrado',
        ],
        'repass' => [
            'required' => 'Repetir contraseña es obligatorio',
            'min_length' => 'Repetir contraseña debe tener como mínimo 8 caracteres',
            'matches' => 'Las contraseñas no coinciden',
        ],
    ]);

    if ($validation->withRequest($request)->run()) {
        $data = [
            'persona_apellido' => $request->getPost('apellido'),
            'persona_nombre' => $request->getPost('nombre'),
            'persona_direccion' => $request->getPost('direccion'),
            'persona_mail' => $request->getPost('correo'),
            'persona_password' => password_hash($request->getPost('pass'), PASSWORD_DEFAULT),
            'perfil_id' => 2,
            'persona_estado' => 1
        ];

        $userRegister = new Usuarios_Model();
        $userRegister->insert($data);
        return redirect()->route('register')->with('mensaje', 'Su registro se realizó exitosamente');
    } else {
        $data['titulo'] = 'Registrarse';
        $data['validation'] = $validation->getErrors();
        return view('plantillas/header', $data)
            .view('registro').view('plantillas/footer');
    }
}

}