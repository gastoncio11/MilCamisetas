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
                    'max_length' => 'El nombre no puede tener más de 50 caracteres',
                    'alpha_space' => 'El nombre solo puede contener letras y espacios'
                ]
            ],
            'apellido' => [
                'rules' => 'required|min_length[2]|max_length[50]|alpha_space',
                'errors' => [
                    'required' => 'El apellido es obligatorio',
                    'min_length' => 'El apellido debe tener al menos 2 caracteres',
                    'max_length' => 'El apellido no puede tener más de 50 caracteres',
                    'alpha_space' => 'El apellido solo puede contener letras y espacios'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[usuarios.email]',
                'errors' => [
                    'required' => 'El correo electrónico es obligatorio',
                    'valid_email' => 'Debe ingresar un correo electrónico válido',
                    'is_unique' => 'Este correo electrónico ya está registrado'
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
                    'required' => 'Debe confirmar su contraseña',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ]
        ]);

        // Validar datos
        if (!$validation->withRequest($this->request)->run()) {
            // Obtener los datos enviados
            $inputData = [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'email' => $this->request->getPost('email')
            ];
            
            // Si hay errores, volver al formulario con los errores y datos
            return view('plantillas/header') .
                   view('registro', [
                       'validation' => $validation,
                       'inputData' => $inputData
                   ]) .
                   view('plantillas/footer');
        }

        // Si la validación pasa, procesar el registro
        $usuarioModel = new \App\Models\Usuario_model();
        
        $datos = [
            'nombre' => trim($this->request->getPost('nombre')),
            'apellido' => trim($this->request->getPost('apellido')),
            'email' => trim(strtolower($this->request->getPost('email'))),
            'pass' => password_hash($this->request->getPost('contraseña'), PASSWORD_DEFAULT),
            'perfil_id' => 0, // Cliente por defecto
        ];

        try {
            $usuarioModel->insert($datos);
            
            return redirect()->to('login')->with('success', 
                'Cuenta creada exitosamente. Ya puedes iniciar sesión.');
                
        } catch (\Exception $e) {
            return redirect()->to('registro')->with('error', 
                'Error al crear la cuenta. Por favor, inténtalo de nuevo.');
        }
    }

    public function login()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe ser un email válido'
                ]
            ],
            'contraseña' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'La contraseña es obligatoria'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('plantillas/header') .
                   view('login', ['validation' => $validation]) .
                   view('plantillas/footer');
        }

        $usuarioModel = new \App\Models\Usuario_model();
        $email = trim(strtolower($this->request->getPost('email')));
        $contraseña = $this->request->getPost('contraseña');

        $usuario = $usuarioModel->where('email', $email)
                               ->where('baja', 'NO')
                               ->first();

        if (!$usuario) {
            return redirect()->to('login')->with('error', 'Email o contraseña incorrectos');
        }

        if (password_verify($contraseña, $usuario['pass'])) {
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
                return redirect()->to('operaciones'); // Admin
            } else {
                return redirect()->to('principal'); // Cliente
            }
        } else {
            return redirect()->to('login')->with('error', 'Email o contraseña incorrectos');
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
        
        // Reglas de validación básicas
        $rules = [
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
                    'required' => 'El correo electrónico es obligatorio',
                    'valid_email' => 'Debe ingresar un correo electrónico válido',
                    'is_unique' => 'Este correo electrónico ya está registrado por otro usuario'
                ]
            ]
        ];

        // Si se proporciona nueva contraseña, agregar validaciones
        if ($this->request->getPost('nueva_contraseña') || $this->request->getPost('contraseña_actual')) {
            $rules['contraseña_actual'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe ingresar su contraseña actual para cambiarla'
                ]
            ];
            $rules['nueva_contraseña'] = [
                'rules' => 'required|min_length[6]|max_length[255]',
                'errors' => [
                    'required' => 'La nueva contraseña es obligatoria',
                    'min_length' => 'La nueva contraseña debe tener al menos 6 caracteres',
                    'max_length' => 'La nueva contraseña es demasiado larga'
                ]
            ];
            $rules['confirmar_nueva_contraseña'] = [
                'rules' => 'required|matches[nueva_contraseña]',
                'errors' => [
                    'required' => 'Debe confirmar la nueva contraseña',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ];
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $usuarioModel = new \App\Models\Usuario_model();
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

        $usuarioModel = new \App\Models\Usuario_model();
        
        // Preparar datos para actualizar
        $data_update = [
            'nombre' => trim($this->request->getPost('nombre')),
            'apellido' => trim($this->request->getPost('apellido')),
            'email' => trim(strtolower($this->request->getPost('email')))
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
            $resultado = $usuarioModel->update($usuario_id, $data_update);
            
            if ($resultado) {
                // Actualizar datos de sesión
                session()->set([
                    'nombre_usuario' => $data_update['nombre'],
                    'email_usuario' => $data_update['email']
                ]);
                
                return redirect()->to('perfil')->with('success', 'Perfil actualizado correctamente');
            } else {
                return redirect()->to('perfil')->with('error', 'No se pudo actualizar el perfil. Inténtalo nuevamente.');
            }
            
        } catch (\Exception $e) {
            return redirect()->to('perfil')->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function historial_compras()
    {
        // Verificar que el usuario esté logueado
        if (!session()->get('usuario_logueado')) {
            return redirect()->to('login')->with('error', 'Debes iniciar sesión para ver tu historial de compras');
        }

        $usuario_id = session()->get('usuario_id');
        
        $ventaModel = new \App\Models\Venta_model();
        $detalleModel = new \App\Models\Detalle_venta_model();
        $datosEnvioModel = new \App\Models\DatosEnvio_model();
        $productoModel = new \App\Models\Producto_model();

        // Obtener todas las compras del usuario
        $compras = $ventaModel->where('id_usuario', $usuario_id)
                             ->orderBy('fecha', 'DESC')
                             ->findAll();

        // Enriquecer cada compra con sus detalles
        foreach ($compras as &$compra) {
            // Obtener productos comprados con nombres
            $detalles = $detalleModel->select('detalle_venta.*, productos.nombre as producto_nombre, productos.imagen')
                                    ->join('productos', 'productos.id = detalle_venta.producto_id', 'left')
                                    ->where('id_venta', $compra['id_venta'])
                                    ->findAll();
            $compra['detalles'] = $detalles;

            // Obtener datos de envío
            $datosEnvio = $datosEnvioModel->where('id_venta', $compra['id_venta'])->first();
            $compra['datos_envio'] = $datosEnvio;
        }

        $data = [
            'compras' => $compras,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data)
             . view('historial_compras')
             . view('plantillas/footer');
    }
}