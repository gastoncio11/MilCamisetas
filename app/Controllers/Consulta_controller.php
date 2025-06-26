<?php
namespace App\Controllers;

use App\Models\Consulta_model;

class Consulta_controller extends BaseController
{
   public function guardar_consulta()
{
    $validation = \Config\Services::validation();
    
    $validation->setRules([
        'nombre' => [
            'rules' => 'required|min_length[2]|max_length[50]|alpha_space',
            'errors' => [
                'required' => 'El nombre es obligatorio',
                'min_length' => 'El nombre debe tener al menos 2 caracteres',
                'max_length' => 'El nombre no puede exceder 50 caracteres',
                'alpha_space' => 'El nombre solo puede contener letras y espacios'
            ]
        ],
        'apellido' => [
            'rules' => 'required|min_length[2]|max_length[50]|alpha_space',
            'errors' => [
                'required' => 'El apellido es obligatorio',
                'min_length' => 'El apellido debe tener al menos 2 caracteres',
                'max_length' => 'El apellido no puede exceder 50 caracteres',
                'alpha_space' => 'El apellido solo puede contener letras y espacios'
            ]
        ],
        'email' => [
            'rules' => 'required|valid_email|max_length[100]',
            'errors' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'Debe ingresar un correo electrónico válido',
                'max_length' => 'El correo electrónico es demasiado largo'
            ]
        ],
        'telefono' => [
            'rules' => 'required|min_length[8]|max_length[20]|regex_match[/^[0-9\s\-\+$$$$]+$/]',
            'errors' => [
                'required' => 'El teléfono es obligatorio',
                'min_length' => 'El teléfono debe tener al menos 8 dígitos',
                'max_length' => 'El teléfono no puede exceder 20 caracteres',
                'regex_match' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis'
            ]
        ],
        'asunto' => [
            'rules' => 'required|min_length[5]|max_length[100]',
            'errors' => [
                'required' => 'El asunto es obligatorio',
                'min_length' => 'El asunto debe tener al menos 5 caracteres',
                'max_length' => 'El asunto no puede exceder 100 caracteres'
            ]
        ],
        'mensaje' => [
            'rules' => 'required|min_length[10]|max_length[1000]',
            'errors' => [
                'required' => 'El mensaje es obligatorio',
                'min_length' => 'El mensaje debe tener al menos 10 caracteres',
                'max_length' => 'El mensaje no puede exceder 1000 caracteres'
            ]
        ]
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->to('contacto')
                        ->withInput()
                        ->with('validation', $validation);
    }

    $consultaModel = new \App\Models\Consulta_model();

    $data = [
        'nombre' => trim($this->request->getPost('nombre')),
        'apellido' => trim($this->request->getPost('apellido')),
        'email' => trim(strtolower($this->request->getPost('email'))),
        'telefono' => trim($this->request->getPost('telefono')),
        'asunto' => trim($this->request->getPost('asunto')),
        'mensaje' => trim($this->request->getPost('mensaje')),
        'fecha' => date('Y-m-d H:i:s'),
        'leida' => 0
    ];

    try {
        $resultado = $consultaModel->insert($data);
        
        if ($resultado) {
            return redirect()->to('contacto')->with('success', 
                '¡Consulta enviada con éxito! Te responderemos a la brevedad.');
        } else {
            return redirect()->to('contacto')->with('error', 
                'No se pudo enviar la consulta. Por favor, inténtalo nuevamente.');
        }
        
    } catch (\Exception $e) {
        log_message('error', 'Error al guardar consulta: ' . $e->getMessage());
        return redirect()->to('contacto')->with('error', 
            'Error al enviar la consulta. Por favor, inténtalo nuevamente.');
    }
}

    public function gestionar_consultas()
    {
        // Verificar que sea admin
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login');
        }

        $consultaModel = new \App\Models\Consulta_model();
        
        $data = [
            'consultas_no_leidas' => $consultaModel->getConsultasNoLeidas(),
            'consultas_leidas' => $consultaModel->getConsultasLeidas(),
            'contador' => $consultaModel->getContadorConsultas(),
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data) .
               view('gestionar_consultas') .
               view('plantillas/footer');
    }

    public function marcar_leida($id)
    {
        // Verificar que sea admin
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login');
        }

        $consultaModel = new \App\Models\Consulta_model();
        $usuario_id = session()->get('usuario_id');
        
        try {
            $consultaModel->marcarComoLeida($id, $usuario_id);
            return redirect()->to('gestionar_consultas')->with('success', 'Consulta marcada como leída');
        } catch (\Exception $e) {
            return redirect()->to('gestionar_consultas')->with('error', 'Error al marcar la consulta');
        }
    }

    public function marcar_no_leida($id)
    {
        // Verificar que sea admin
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login');
        }

        $consultaModel = new \App\Models\Consulta_model();
        
        try {
            $consultaModel->marcarComoNoLeida($id);
            return redirect()->to('gestionar_consultas')->with('success', 'Consulta marcada como no leída');
        } catch (\Exception $e) {
            return redirect()->to('gestionar_consultas')->with('error', 'Error al marcar la consulta');
        }
    }
}