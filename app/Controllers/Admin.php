<?php

namespace App\Controllers;

use App\Models\usuario_model;
use App\Models\Consulta_model;
use CodeIgniter\Controller;
use App\Models\ProductoTalle_model;

class Admin extends Controller
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function operaciones()
    {
        $data = [
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('id_perfil'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data).
               view('operaciones').
               view('plantillas/footer');
    }

    public function gestionar_usuarios()
    {
        $usuarioModel = new usuario_model();
        $usuarios = $usuarioModel->orderBy('id_usuario', 'DESC')->findAll();

        $data = [
            'usuarios' => $usuarios,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data).
               view('gestionar_usuarios').
               view('plantillas/footer');
    }

    public function crear_usuario()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|min_length[2]|max_length[50]|alpha_space',
            'apellido' => 'required|min_length[2]|max_length[50]|alpha_space',
            'email' => 'required|valid_email|is_unique[usuarios.email]',
            'contraseña' => 'required|min_length[6]',
            'perfil_id' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = implode(', ', $validation->getErrors());
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error: ' . $errors);
        }

        $usuarioModel = new usuario_model();
        
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email'),
            'pass' => password_hash($this->request->getPost('contraseña'), PASSWORD_DEFAULT),
            'perfil_id' => $this->request->getPost('perfil_id'),
            'baja' => 'NO'
        ];

        try {
            $usuarioModel->insert($data);
            return redirect()->to('admin/gestionar_usuarios')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error al crear el usuario');
        }
    }

    public function editar_usuario($id)
    {
        $usuarioModel = new usuario_model();
        $usuario = $usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Usuario no encontrado');
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|min_length[2]|max_length[50]|alpha_space',
            'apellido' => 'required|min_length[2]|max_length[50]|alpha_space',
            'email' => 'required|valid_email|is_unique[usuarios.email,id_usuario,' . $id . ']',
            'perfil_id' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = implode(', ', $validation->getErrors());
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error: ' . $errors);
        }

        $data_update = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email'),
            'perfil_id' => $this->request->getPost('perfil_id')
        ];

        // Solo actualizar contraseña si se proporciona
        $nueva_contraseña = $this->request->getPost('contraseña');
        if (!empty($nueva_contraseña)) {
            $data_update['pass'] = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        }

        try {
            $usuarioModel->update($id, $data_update);
            return redirect()->to('admin/gestionar_usuarios')->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error al actualizar el usuario');
        }
    }

        public function toggle_estado_usuario($id)
    {
        $usuarioModel = new usuario_model();
        $usuario = $usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Usuario no encontrado');
        }

        // No permitir modificar el estado del admin actual
        if ($id == session()->get('usuario_id')) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'No puedes modificar tu propio estado');
        }

        $nuevo_estado = $usuario['baja'] === 'NO' ? 'SI' : 'NO';

        try {
            $usuarioModel->update($id, ['baja' => $nuevo_estado]);
            $mensaje = $nuevo_estado === 'SI' ? 'Usuario dado de baja' : 'Usuario dado de alta';
            return redirect()->to('admin/gestionar_usuarios')->with('success', $mensaje);
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error al actualizar estado');
        }
    }


    public function obtener_usuario($id)
    {
        // Limpiar cualquier output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        $usuarioModel = new usuario_model();
        $usuario = $usuarioModel->find($id);

        if (!$usuario) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Usuario no encontrado']);
            exit;
        }

        $userData = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'email' => $usuario['email'],
            'perfil_id' => $usuario['perfil_id']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($userData);
        exit;
    }

    public function gestionar_productos()
    {
        $productoModel = new \App\Models\Producto_model();
        $categoriaModel = new \App\Models\Categoria_model();
        
        // Obtener productos sin duplicados
        $productos = $productoModel->getProductosParaAdmin();
        
        // Obtener categorías activas
        $categorias = $categoriaModel->where('activacion', 1)->findAll();
        
        // Usar ProductoTalle_model consistentemente
        $stockModel = new ProductoTalle_model();
        foreach ($productos as &$producto) {
            $stock_detalle = $stockModel->getStockPorProducto($producto['id']);
            $producto['stock_detalle'] = $stock_detalle;
            
            // Calcular stock total en tiempo real
            $producto['stock_total'] = array_sum(array_column($stock_detalle, 'stock'));
        }

        $data = [
            'productos' => $productos,
            'categorias' => $categorias,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data).
               view('gestionar_productos').
               view('plantillas/footer');
    }

    public function crear_producto()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|min_length[3]|max_length[100]',
            'precio' => 'required|decimal',
            'descripcion' => 'required|min_length[10]',
            'id_categoria' => 'required|integer',
            'imagen' => 'uploaded[imagen]|is_image[imagen]|max_size[imagen,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = implode(', ', $validation->getErrors());
            return redirect()->to('admin/gestionar_productos')->with('error', 'Error: ' . $errors);
        }

        $productoModel = new \App\Models\Producto_model();
        $stockModel = new ProductoTalle_model();
        
        // Manejar subida de imagen
        $imagen = $this->request->getFile('imagen');
        $nombreImagen = '';
        
        if ($imagen->isValid() && !$imagen->hasMoved()) {
            $nombreImagen = $imagen->getRandomName();
            $imagen->move(ROOTPATH . 'public/assets/img/productos/', $nombreImagen);
        }

        $data_producto = [
            'nombre' => $this->request->getPost('nombre'),
            'precio' => $this->request->getPost('precio'),
            'descripcion' => $this->request->getPost('descripcion'),
            'imagen' => $nombreImagen,
            'id_categoria' => $this->request->getPost('id_categoria'),
            'activo' => 1,
            'stock_total' => 0
        ];

        try {
            $producto_id = $productoModel->insert($data_producto);
            
            // Determinar talles según categoría
            $id_categoria = $this->request->getPost('id_categoria');
            $categoriaModel = new \App\Models\Categoria_model();
            $categoria = $categoriaModel->find($id_categoria);

            $esKids = (
                $id_categoria == 4 || 
                ($categoria && strtolower($categoria['ct_nombre']) === 'kids')
            );
            
            $talles = $esKids ? ['8', '10', '12', '14'] : ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

            foreach ($talles as $talle) {
                $stockModel->insert([
                    'producto_id' => $producto_id,
                    'talle' => $talle,
                    'stock' => 0
                ]);
            }

            return redirect()->to('admin/gestionar_productos')->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_productos')->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function obtener_producto($id)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $productoModel = new \App\Models\Producto_model();
        $stockModel = new ProductoTalle_model();
        
        $producto = $productoModel->getProductoConDetalles($id);
        
        if (!$producto) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Producto no encontrado']);
            exit;
        }

        $stock_detalle = $stockModel->getStockPorProducto($id);
        $producto['stock_detalle'] = $stock_detalle;
        
        // Info para determinar si es Kids
        $producto['debug_categoria_id'] = $producto['id_categoria'];
        $producto['debug_categoria_nombre'] = $producto['categoria_nombre'];
        $producto['debug_es_kids'] = (
            $producto['id_categoria'] == 4 || 
            strtolower($producto['categoria_nombre'] ?? '') === 'kids'
        );
        
        header('Content-Type: application/json');
        echo json_encode($producto);
        exit;
    }

    public function actualizar_stock()
    {
        $producto_id = $this->request->getPost('producto_id');
        $stocks = $this->request->getPost('stock');

        if (empty($producto_id) || empty($stocks) || !is_array($stocks)) {
            return redirect()->back()->with('error', 'Datos de stock inválidos');
        }

        $db = \Config\Database::connect();

        try {
            $db->transStart();
            
            $actualizacionesExitosas = 0;
            
            foreach ($stocks as $talle => $cantidad) {
                if (empty($talle) || $talle === null || $talle === '') {
                    continue;
                }
                
                $cantidad = (int)$cantidad;
                if ($cantidad < 0) {
                    continue;
                }
                
                $existing = $db->query("SELECT id FROM producto_talle WHERE producto_id = ? AND talle = ?", [$producto_id, $talle])->getRow();
                
                if ($existing) {
                    $result = $db->query("UPDATE producto_talle SET stock = ? WHERE id = ?", [$cantidad, $existing->id]);
                } else {
                    $result = $db->query("INSERT INTO producto_talle (producto_id, talle, stock) VALUES (?, ?, ?)", [$producto_id, $talle, $cantidad]);
                }
                
                if ($result) {
                    $actualizacionesExitosas++;
                }
            }
            
            if ($actualizacionesExitosas === 0) {
                throw new \RuntimeException("No se pudo actualizar ningún talle");
            }
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException("Error en la transacción");
            }

            // Recalcular stock total
            $stockTotal = $db->query("SELECT SUM(stock) as total FROM producto_talle WHERE producto_id = ? AND talle != '' AND talle IS NOT NULL", [$producto_id])->getRow()->total ?? 0;
            
            // Actualizar tabla productos
            $db->query("UPDATE productos SET stock_total = ? WHERE id = ?", [$stockTotal, $producto_id]);

            return redirect()->back()
                   ->with('success', "✅ Stock actualizado correctamente ({$actualizacionesExitosas} talles)")
                   ->with('nuevoTotal', $stockTotal);

        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', '❌ Error al actualizar stock: ' . $e->getMessage());
        }
    }

    public function gestionar_ventas()
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos de administrador');
        }

        $ventaModel = new \App\Models\Venta_model();
        $detalleModel = new \App\Models\Detalle_venta_model();
        $usuarioModel = new \App\Models\Usuario_model();
        $datosEnvioModel = new \App\Models\DatosEnvio_model();
        $productoModel = new \App\Models\Producto_model();

        // Obtener filtros de la URL
        $mes = $this->request->getGet('mes');
        $anio = $this->request->getGet('anio');

        // Construir query base
        $builder = $ventaModel->builder();
        $builder->select('ventas.*, usuarios.nombre, usuarios.apellido, usuarios.email')
                ->join('usuarios', 'usuarios.id_usuario = ventas.id_usuario', 'left');

        // Aplicar filtros si existen
        if ($mes && $anio) {
            $builder->where('MONTH(ventas.fecha)', $mes)
                    ->where('YEAR(ventas.fecha)', $anio);
        } elseif ($anio) {
            $builder->where('YEAR(ventas.fecha)', $anio);
        }

        $ventas = $builder->orderBy('ventas.fecha', 'DESC')->get()->getResultArray();

        // Enriquecer datos de cada venta
        foreach ($ventas as &$venta) {
            // Obtener detalles de la venta con nombres de productos
            $detalles = $detalleModel->select('detalle_venta.*, productos.nombre as producto_nombre')
                                    ->join('productos', 'productos.id = detalle_venta.producto_id', 'left')
                                    ->where('id_venta', $venta['id_venta'])
                                    ->findAll();
            $venta['detalles'] = $detalles;

            // Obtener datos de envío
            $datosEnvio = $datosEnvioModel->where('id_venta', $venta['id_venta'])->first();
            $venta['datos_envio'] = $datosEnvio;
        }

        // Obtener años disponibles para el filtro
        $aniosDisponibles = $ventaModel->select('DISTINCT YEAR(fecha) as anio')
                                      ->orderBy('anio', 'DESC')
                                      ->findAll();

        // Calcular estadísticas del mes actual si hay filtro
        $estadisticas = [];
        if ($mes && $anio) {
            $totalVentas = count($ventas);
            $totalIngresos = array_sum(array_column($ventas, 'total'));
            $estadisticas = [
                'total_ventas' => $totalVentas,
                'total_ingresos' => $totalIngresos,
                'promedio_venta' => $totalVentas > 0 ? $totalIngresos / $totalVentas : 0,
                'mes_nombre' => $this->getNombreMes($mes),
                'anio' => $anio
            ];
        }

        $data = [
            'ventas' => $ventas,
            'anios_disponibles' => $aniosDisponibles,
            'mes_actual' => $mes,
            'anio_actual' => $anio,
            'estadisticas' => $estadisticas,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data)
            . view('gestionar_ventas')
            . view('plantillas/footer');
    }

    private function getNombreMes($numeroMes)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[$numeroMes] ?? '';
    }

    public function gestionar_consultas()
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos de administrador');
        }

        try {
            $consultaModel = new Consulta_model();
            $consultas = $consultaModel->orderBy('fecha', 'DESC')->findAll();
            
            $data = [
                'consultas' => $consultas,
                'usuario_logueado' => session()->get('usuario_logueado'),
                'perfil_id' => session()->get('perfil_id'),
                'nombre_usuario' => session()->get('nombre_usuario')
            ];

            return view('plantillas/header', $data) .
                   view('gestionar_consultas') .
                   view('plantillas/footer');
                   
        } catch (\Exception $e) {
            $data = [
                'consultas' => [],
                'usuario_logueado' => session()->get('usuario_logueado'),
                'perfil_id' => session()->get('perfil_id'),
                'nombre_usuario' => session()->get('nombre_usuario')
            ];
            
            return view('plantillas/header', $data) .
                   view('gestionar_consultas') .
                   view('plantillas/footer');
        }
    }

    public function marcar_leida($id)
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos');
        }

        try {
            $consultaModel = new Consulta_model();
            $resultado = $consultaModel->update($id, ['leida' => 1]);
            
            if ($resultado) {
                return redirect()->to('admin/gestionar_consultas')
                               ->with('success', '✅ Consulta marcada como leída correctamente');
            } else {
                return redirect()->to('admin/gestionar_consultas')
                               ->with('error', '❌ No se pudo marcar la consulta como leída');
            }
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_consultas')
                           ->with('error', '❌ Error al actualizar la consulta: ' . $e->getMessage());
        }
    }

    public function marcar_no_leida($id)
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos');
        }

        try {
            $consultaModel = new Consulta_model();
            $resultado = $consultaModel->update($id, ['leida' => 0]);
            
            if ($resultado) {
                return redirect()->to('admin/gestionar_consultas')
                               ->with('success', '📩 Consulta marcada como no leída correctamente');
            } else {
                return redirect()->to('admin/gestionar_consultas')
                               ->with('error', '❌ No se pudo marcar la consulta como no leída');
            }
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_consultas')
                           ->with('error', '❌ Error al actualizar la consulta: ' . $e->getMessage());
        }
    }

    public function toggle_producto_activo($id)
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos');
        }

        try {
            $productoModel = new \App\Models\Producto_model();
            $resultado = $productoModel->toggleActivo($id);
            
            if ($resultado) {
                return redirect()->to('admin/gestionar_productos')
                               ->with('success', '✅ Estado del producto actualizado correctamente');
            } else {
                return redirect()->to('admin/gestionar_productos')
                               ->with('error', '❌ No se pudo actualizar el estado del producto');
            }
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_productos')
                           ->with('error', '❌ Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function reparar_kids()
    {
        if (!session()->get('usuario_logueado') || session()->get('perfil_id') != 1) {
            return redirect()->to('login')->with('error', 'No tienes permisos');
        }

        try {
            $stockModel = new ProductoTalle_model();
            $productoModel = new \App\Models\Producto_model();
            
            $productos = $productoModel->select('productos.*, categoria.ct_nombre as categoria_nombre')
                                      ->join('categoria', 'categoria.id_categoria = productos.id_categoria', 'left')
                                      ->where('productos.id_categoria', 4)
                                      ->orWhere('LOWER(categoria.ct_nombre)', 'kids')
                                      ->findAll();
            
            $reparados = 0;
            foreach ($productos as $producto) {
                $stockModel->limpiarTallesProblematicos($producto['id']);
                $stockModel->crearTallesKids($producto['id']);
                $stockModel->actualizarStockTotal($producto['id']);
                $reparados++;
            }
            
            return redirect()->to('admin/gestionar_productos')
                   ->with('success', "✅ {$reparados} productos Kids reparados correctamente");
                   
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_productos')
                   ->with('error', '❌ Error al reparar productos Kids: ' . $e->getMessage());
        }
    }

    public function editar_producto()
{
    $productoModel = new \App\Models\Producto_model();
    $stockModel = new \App\Models\ProductoTalle_model();

    $id = $this->request->getPost('id');

    $producto = $productoModel->find($id);
    if (!$producto) {
        return redirect()->to('admin/gestionar_productos')->with('error', 'Producto no encontrado');
    }

    $validation = \Config\Services::validation();
    $validation->setRules([
        'nombre' => 'required|min_length[3]|max_length[100]',
        'precio' => 'required|decimal',
        'descripcion' => 'required|min_length[1]',
        'id_categoria' => 'required|integer'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        $errors = implode(', ', $validation->getErrors());
        return redirect()->to('admin/gestionar_productos')->with('error', 'Error: ' . $errors);
    }

    $imagen = $this->request->getFile('imagen');
    $nombreImagen = $producto['imagen']; // Mantener la imagen actual

    if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
        $nombreImagen = $imagen->getRandomName();
        $imagen->move(ROOTPATH . 'public/assets/img/productos/', $nombreImagen);

        // Podés borrar la imagen anterior si querés:
        // @unlink(ROOTPATH . 'public/assets/img/productos/' . $producto['imagen']);
    }

    $dataUpdate = [
        'nombre' => $this->request->getPost('nombre'),
        'precio' => $this->request->getPost('precio'),
        'descripcion' => $this->request->getPost('descripcion'),
        'id_categoria' => $this->request->getPost('id_categoria'),
        'imagen' => $nombreImagen
    ];

    try {
        $productoModel->update($id, $dataUpdate);
        return redirect()->to('admin/gestionar_productos')->with('success', '✅ Producto actualizado correctamente');
    } catch (\Exception $e) {
        return redirect()->to('admin/gestionar_productos')->with('error', '❌ Error al actualizar el producto: ' . $e->getMessage());
    }
}

}
