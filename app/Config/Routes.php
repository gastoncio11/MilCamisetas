<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('nosotros', 'Home::nosotros');
$routes->get('contacto', 'Home::contacto');
$routes->get('principal', 'Home::principal');
$routes->get('terminosdeuso', 'Home::terminosdeuso');
$routes->get('comercio', 'Home::comercio');
$routes->get('login', 'Home::login');
$routes->get('registro', 'Home::registro');
$routes->get('operaciones', 'Home::operaciones');
$routes->post('usuario/registrar', 'Usuario::registrar');
$routes->post('usuario/login', 'Usuario::login');
$routes->get('usuario/logout', 'Usuario::logout');

// Rutas protegidas
$routes->get('perfil', 'Usuario::perfil');
$routes->post('usuario/actualizar_perfil', 'Usuario::actualizar_perfil');


$routes->get('catalogo', 'Producto_controller::catalogo');
$routes->get('producto/(:num)', 'Producto_controller::producto/$1');

$routes->get('carrito', 'Carrito_controller::ver_carrito');
$routes->post('agregar-carrito', 'Carrito_controller::agregar_carrito');
$routes->post('guardar-venta', 'Carrito_controller::guardar_venta');
$routes->get('carrito/eliminar/(:any)', 'Carrito_controller::eliminar_item/$1');
$routes->get('carrito/vaciar', 'Carrito_controller::vaciar_carrito');


// Rutas de administración

// Panel principal de operaciones
$routes->get('operaciones', 'Admin::operaciones');

// Gestión de usuarios
$routes->get('admin/gestionar_usuarios', 'Admin::gestionar_usuarios');
$routes->post('admin/crear_usuario', 'Admin::crear_usuario');
$routes->post('admin/editar_usuario/(:num)', 'Admin::editar_usuario/$1');
$routes->get('admin/eliminar_usuario/(:num)', 'Admin::eliminar_usuario/$1');
$routes->get('admin/obtener_usuario/(:num)', 'Admin::obtener_usuario/$1');

// Gestión de productos
$routes->get('admin/gestionar_productos', 'Admin::gestionar_productos');

// Gestión de consultas
$routes->get('admin/gestionar_consultas', 'Admin::gestionar_consultas');
$routes->post('guardar_consulta', 'Consulta_controller::guardar_consulta');


// Gestión de ventas
$routes->get('admin/gestionar_ventas', 'Admin::gestionar_ventas');

// Gestión de productos
$routes->get('admin/gestionar_productos', 'Admin::gestionar_productos');
$routes->post('admin/crear_producto', 'Admin::crear_producto');
$routes->get('admin/obtener_producto/(:num)', 'Admin::obtener_producto/$1');
$routes->post('admin/editar_producto', 'Admin::editar_producto');
$routes->post('admin/actualizar_stock', 'Admin::actualizar_stock');
$routes->get('admin/eliminar_producto/(:num)', 'Admin::eliminar_producto/$1');