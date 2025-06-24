<?php
namespace App\Controllers;

use App\Models\Consulta_model;

class Consulta_controller extends BaseController
{
    public function guardar_consulta()
    {
        $consultaModel = new \App\Models\Consulta_model();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'asunto' => $this->request->getPost('asunto'),
            'mensaje' => $this->request->getPost('mensaje'),
        ];

        $consultaModel->insert($data);
        return redirect()->to('contacto')->with('success', 'Consulta enviada con éxito');
    }

}