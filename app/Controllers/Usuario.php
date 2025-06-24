<?php

namespace App\Controllers;

use App\Models\usuario_model;
use CodeIgniter\Controller;

class Usuario extends Controller
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

 public function registrar()
    {
        $validation = \Config\Services::validation();
        
        // Reglas de validación
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
                'rules' => 'required|valid_email|is_unique[usuarios.email]',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe ingresar un email válido',
                    'is_unique' => 'Este email ya está registrado'
                ]
            ],
            'contraseña' => [
                'rules' => 'required|min_length[6]|max_length[255]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria',
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres',
                    'max_length' => 'La contraseña es demasiado larga'
                ]
            ],
            'confirmar_contraseña' => [
                'rules' => 'required|matches[contraseña]',
                'errors' => [
                    'required' => 'Debe confirmar la contraseña',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Si hay errores de validación, mostrar la vista con errores
            $data['validation'] = $validation;
            return view('registro', $data);
        }

        // Si la validación es exitosa, procesar el registro
        $usuarioModel = new usuario_model();
        
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email'),
            'pass' => password_hash($this->request->getPost('contraseña'), PASSWORD_DEFAULT),
            'perfil_id' => 0, // 0 para cliente por defecto
            'baja' => 'NO'
        ];

        try {
            $usuarioModel->insert($data);
            
            // Registro exitoso - mostrar mensaje en la vista de registro
            $data['success'] = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
             return view('plantillas/header', $data).
           view('registro').
           view('plantillas/footer');
            
        } catch (\Exception $e) {
            // Error en la base de datos - mostrar vista con error
            $data['error'] = 'Error al registrar el usuario. Inténtalo nuevamente.';
             return view('plantillas/header', $data).
           view('registro').
           view('plantillas/footer');
        }
    }


    public function login()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'contraseña' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data['validation'] = $validation;
            return view('login', $data);
        }

        $usuarioModel = new usuario_model();
        $email = $this->request->getPost('email');
        $contraseña = $this->request->getPost('contraseña');

        $usuario = $usuarioModel->where('email', $email)
                                ->where('baja', 'NO')
                                ->first();

        if ($usuario && password_verify($contraseña, $usuario['pass'])) {
            // Login exitoso - crear sesión
            $session = session();
            $session->set([
                'usuario_logueado' => true,
                'usuario_id' => $usuario['id_usuario'],
                'nombre_usuario' => $usuario['nombre'],
                'email_usuario' => $usuario['email'],
                'perfil_id' => $usuario['perfil_id']
            ]);

            // Redirigir según el tipo de usuario
            if ($usuario['perfil_id'] == 1) {
                return redirect()->to('principal'); // Admin
            } else {
                return redirect()->to('principal'); // Cliente
            }
        } else {
            $data['error'] = 'Email o contraseña incorrectos';
            return view('login', $data);
        }
    }

  public function logout()
{
    $session = session();
    $session->destroy();
    
    // Forzar limpieza de variables de sesión
    $session->remove('usuario_logueado');
    $session->remove('perfil_id');
    $session->remove('nombre_usuario');
    $session->remove('usuario_id');
    $session->remove('email_usuario');
    
    // Crear datos limpios para el header
    $data = [
        'usuario_logueado' => false,
        'perfil_id' => null,
        'nombre_usuario' => null
    ];
    
    return view('plantillas/header', $data).
           view('login').
           view('plantillas/footer');
}

public function perfil()
{
    // Verificar que el usuario esté logueado
    if (!session()->get('usuario_logueado')) {
        return redirect()->to('login');
    }

    $usuarioModel = new usuario_model();
    $usuario_id = session()->get('usuario_id');
    
    // Obtener datos actuales del usuario
    $usuario = $usuarioModel->find($usuario_id);
    
    if (!$usuario) {
        $data['error'] = 'Usuario no encontrado';
        return view('plantillas/header', $data).
               view('error').
               view('plantillas/footer');
    }

    $data = [
        'usuario' => $usuario,
        'usuario_logueado' => session()->get('usuario_logueado'),
        'perfil_id' => session()->get('perfil_id'),
        'nombre_usuario' => session()->get('nombre_usuario')
    ];

    return view('plantillas/header', $data).
           view('perfil').
           view('plantillas/footer');
}

public function actualizar_perfil()
{
    // Verificar que el usuario esté logueado
    if (!session()->get('usuario_logueado')) {
        return redirect()->to('login');
    }

    $validation = \Config\Services::validation();
    $usuario_id = session()->get('usuario_id');
    
    // Reglas de validación
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
            'rules' => 'required|valid_email|is_unique[usuarios.email,id_usuario,' . $usuario_id . ']',
            'errors' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Debe ingresar un email válido',
                'is_unique' => 'Este email ya está registrado por otro usuario'
            ]
        ]
    ]);

    // Si se proporciona nueva contraseña, validarla
    if ($this->request->getPost('nueva_contraseña')) {
        $validation->setRules([
            'contraseña_actual' => 'required',
            'nueva_contraseña' => 'required|min_length[6]|max_length[255]',
            'confirmar_nueva_contraseña' => 'required|matches[nueva_contraseña]'
        ]);
    }

    if (!$validation->withRequest($this->request)->run()) {
        $usuarioModel = new usuario_model();
        $usuario = $usuarioModel->find($usuario_id);
        
        $data = [
            'validation' => $validation,
            'usuario' => $usuario,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];
        
        return view('plantillas/header', $data).
               view('perfil').
               view('plantillas/footer');
    }

    $usuarioModel = new usuario_model();
    
    // Preparar datos para actualizar
    $data_update = [
        'nombre' => $this->request->getPost('nombre'),
        'apellido' => $this->request->getPost('apellido'),
        'email' => $this->request->getPost('email')
    ];

    // Si se proporciona nueva contraseña, verificar la actual y actualizar
    if ($this->request->getPost('nueva_contraseña')) {
        $usuario_actual = $usuarioModel->find($usuario_id);
        $contraseña_actual = $this->request->getPost('contraseña_actual');
        
        if (!password_verify($contraseña_actual, $usuario_actual['pass'])) {
            $data = [
                'error' => 'La contraseña actual es incorrecta',
                'usuario' => $usuario_actual,
                'usuario_logueado' => session()->get('usuario_logueado'),
                'perfil_id' => session()->get('perfil_id'),
                'nombre_usuario' => session()->get('nombre_usuario')
            ];
            
            return view('plantillas/header', $data).
                   view('perfil').
                   view('plantillas/footer');
        }
        
        $data_update['pass'] = password_hash($this->request->getPost('nueva_contraseña'), PASSWORD_DEFAULT);
    }

    try {
        $usuarioModel->update($usuario_id, $data_update);
        
        // Actualizar datos de sesión
        session()->set([
            'nombre_usuario' => $data_update['nombre'],
            'email_usuario' => $data_update['email']
        ]);
        
        $usuario = $usuarioModel->find($usuario_id);
        $data = [
            'success' => 'Perfil actualizado correctamente',
            'usuario' => $usuario,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];
        
        return view('plantillas/header', $data).
               view('perfil').
               view('plantillas/footer');
        
    } catch (\Exception $e) {
        $usuario = $usuarioModel->find($usuario_id);
        $data = [
            'error' => 'Error al actualizar el perfil. Inténtalo nuevamente.',
            'usuario' => $usuario,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];
        
        return view('plantillas/header', $data).
               view('perfil').
               view('plantillas/footer');
    }
}
}