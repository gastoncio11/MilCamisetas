<?php

namespace App\Controllers;

use App\Models\usuario_model;
use CodeIgniter\Controller;

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
            'perfil_id' => session()->get('perfil_id'),
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

    public function eliminar_usuario($id)
    {
        $usuarioModel = new usuario_model();
        $usuario = $usuarioModel->find($id);

        if (!$usuario) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Usuario no encontrado');
        }

        // No permitir eliminar al usuario actual
        if ($id == session()->get('usuario_id')) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'No puedes eliminarte a ti mismo');
        }

        try {
            $usuarioModel->update($id, ['baja' => 'SI']);
            return redirect()->to('admin/gestionar_usuarios')->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('admin/gestionar_usuarios')->with('error', 'Error al eliminar el usuario');
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
    $stockModel = new \App\Models\ProductoStock_model();
    
    $productos = $productoModel->getProductosConCategoria();
    
    // Obtener categorías activas (asumiendo que activacion = 1 significa activa)
    $categorias = $categoriaModel->where('activacion', 1)->findAll();
    
    // Obtener stock para cada producto
    foreach ($productos as &$producto) {
        $stock_detalle = $stockModel->getStockPorProducto($producto['id']);
        $producto['stock_detalle'] = $stock_detalle;
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
    $stockModel = new \App\Models\ProductoStock_model();
    
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
        'activo' => 1
    ];

    try {
        $producto_id = $productoModel->insert($data_producto);
        
        // Crear stock inicial para talles estándar
        $id_categoria = $this->request->getPost('id_categoria');

        // Verificamos si es categoría "Kids" (ajustá este ID según tu base)
        $esKids = false;
        $categoriaModel = new \App\Models\Categoria_model();
        $categoria = $categoriaModel->find($id_categoria);

        if ($categoria && strtolower($categoria['ct_nombre']) === 'kids') {
            $esKids = true;
        }

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
    $stockModel = new \App\Models\ProductoStock_model();
    
    $producto = $productoModel->getProductoConDetalles($id);
    
    if (!$producto) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Producto no encontrado']);
        exit;
    }

    $stock_detalle = $stockModel->getStockPorProducto($id);
    $producto['stock_detalle'] = $stock_detalle;
    
    header('Content-Type: application/json');
    echo json_encode($producto);
    exit;
}

public function actualizar_stock()
{
    $stockModel = new \App\Models\ProductoStock_model();
    
    $producto_id = $this->request->getPost('producto_id');
    $stocks = $this->request->getPost('stock');
    
    try {
        foreach ($stocks as $talle => $cantidad) {
            $stockModel->actualizarStock($producto_id, $talle, $cantidad);
        }
        
        // Actualizar stock total en la tabla productos
        $productoModel = new \App\Models\Producto_model();
        $stock_total = array_sum($stocks);
        $productoModel->update($producto_id, ['stock_total' => $stock_total]);
        
        return redirect()->to('admin/gestionar_productos')->with('success', 'Stock actualizado exitosamente');
    } catch (\Exception $e) {
        return redirect()->to('admin/gestionar_productos')->with('error', 'Error al actualizar el stock: ' . $e->getMessage());
    }
}

public function eliminar_producto($id)
{
    $productoModel = new \App\Models\Producto_model();
    
    try {
        // Eliminación lógica
        $productoModel->update($id, ['activo' => 0]);
        return redirect()->to('admin/gestionar_productos')->with('success', 'Producto eliminado exitosamente');
    } catch (\Exception $e) {
        return redirect()->to('admin/gestionar_productos')->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
    }
}

public function editar_producto()
{
    $validation = \Config\Services::validation();
    
    $validation->setRules([
        'id' => 'required|integer',
        'nombre' => 'required|min_length[3]|max_length[100]',
        'precio' => 'required|decimal',
        'descripcion' => 'required|min_length[10]',
        'id_categoria' => 'required|integer'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        $errors = implode(', ', $validation->getErrors());
        return redirect()->to('admin/gestionar_productos')->with('error', 'Error: ' . $errors);
    }

    $productoModel = new \App\Models\Producto_model();
    
    $data_producto = [
        'nombre' => $this->request->getPost('nombre'),
        'precio' => $this->request->getPost('precio'),
        'descripcion' => $this->request->getPost('descripcion'),
        'id_categoria' => $this->request->getPost('id_categoria')
    ];

    // Manejar imagen si se subió una nueva
    $imagen = $this->request->getFile('imagen');
    if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
        $nombreImagen = $imagen->getRandomName();
        $imagen->move(ROOTPATH . 'public/assets/img/productos/', $nombreImagen);
        $data_producto['imagen'] = $nombreImagen;
    }

    try {
        $id = $this->request->getPost('id');
        $productoModel->update($id, $data_producto);
        
        return redirect()->to('admin/gestionar_productos')->with('success', 'Producto actualizado exitosamente');
    } catch (\Exception $e) {
        return redirect()->to('admin/gestionar_productos')->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
    }
}

    public function gestionar_ventas()
    {
        $ventaModel = new \App\Models\Venta_model();
        $detalleModel = new \App\Models\Detalle_venta_model();
        $usuarioModel = new \App\Models\Usuario_model();

        $ventas = $ventaModel->orderBy('fecha', 'DESC')->findAll();

        foreach ($ventas as &$venta) {
            $venta['usuario'] = $usuarioModel->find($venta['id_usuario']);
            $venta['detalles'] = $detalleModel
                ->where('id_venta', $venta['id_venta'])
                ->findAll();
        }

        $data = [
            'ventas' => $ventas,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data)
            . view('gestionar_ventas')
            . view('plantillas/footer');
    }

    public function gestionar_consultas()
    {
        $consultaModel = new \App\Models\Consulta_model();
        $consultas = $consultaModel->orderBy('fecha', 'DESC')->findAll();

        $data = [
            'consultas' => $consultas,
            'usuario_logueado' => session()->get('usuario_logueado'),
            'perfil_id' => session()->get('perfil_id'),
            'nombre_usuario' => session()->get('nombre_usuario')
        ];

        return view('plantillas/header', $data)
            . view('gestionar_consultas')
            . view('plantillas/footer');
    }


}