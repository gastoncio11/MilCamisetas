<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Página principal
$routes->get('/', 'Home::index');
$routes->get('principal', 'Home::index');

// Páginas informativas
$routes->get('nosotros', 'Home::nosotros');
$routes->get('quienes_somos', 'Home::quienes_somos');
$routes->get('acerca_de', 'Home::acerca_de');
$routes->get('terminosdeuso', 'Home::terminosdeuso');
$routes->get('comercio', 'Home::comercio');
$routes->get('contacto', 'Home::contacto');
$routes->post('enviar_consulta', 'Home::enviar_consulta');

// Autenticación y perfil de usuario
$routes->get('login', 'Usuario::login');
$routes->post('usuario/login', 'Usuario::login');
$routes->get('registro', 'Usuario::registrar');
$routes->post('usuario/registrar', 'Usuario::registrar');
$routes->get('logout', 'Usuario::logout');

$routes->get('perfil', 'Usuario::perfil');
$routes->post('usuario/actualizar_perfil', 'Usuario::actualizar_perfil');

// Historial de compras
$routes->get('usuario/historial_compras', 'Usuario::historial_compras');
$routes->get('historial-compras', 'Usuario::historial_compras'); 

// Catálogo y productos
$routes->get('catalogo', 'Producto_controller::catalogo');
$routes->get('producto/(:num)', 'Producto_controller::producto/$1');
$routes->get('categoria/(:num)', 'Home::categoria/$1');

// Carrito
$routes->get('carrito', 'Carrito_controller::ver_carrito');
$routes->post('agregar-carrito', 'Carrito_controller::agregar_carrito');
$routes->post('carrito/agregar', 'Carrito_controller::agregar_carrito'); 
$routes->post('guardar-venta', 'Carrito_controller::guardar_venta');
$routes->get('carrito/eliminar/(:any)', 'Carrito_controller::eliminar_item/$1');
$routes->get('carrito/vaciar', 'Carrito_controller::vaciar_carrito');

// Consultas
$routes->post('guardar_consulta', 'Consulta_controller::guardar_consulta');

// Administración: operaciones
$routes->get('operaciones', 'Admin::operaciones');

// Administración: usuarios
$routes->get('admin/gestionar_usuarios', 'Admin::gestionar_usuarios');
$routes->post('admin/crear_usuario', 'Admin::crear_usuario');
$routes->post('admin/editar_usuario/(:num)', 'Admin::editar_usuario/$1');
$routes->get('admin/eliminar_usuario/(:num)', 'Admin::eliminar_usuario/$1');
$routes->get('admin/obtener_usuario/(:num)', 'Admin::obtener_usuario/$1');
$routes->post('admin/toggle-estado-usuario/(:num)', 'Admin::toggle_estado_usuario/$1');

// Administración: productos
$routes->get('admin/gestionar_productos', 'Admin::gestionar_productos');
$routes->post('admin/crear_producto', 'Admin::crear_producto');
$routes->post('admin/editar_producto', 'Admin::editar_producto');
$routes->get('admin/eliminar_producto/(:num)', 'Admin::eliminar_producto/$1');
$routes->get('admin/obtener_producto/(:num)', 'Admin::obtener_producto/$1');
$routes->post('admin/actualizar_stock', 'Admin::actualizar_stock');
$routes->get('admin/toggle_producto_activo/(:num)', 'Admin::toggle_producto_activo/$1');

// Administración: ventas
$routes->get('admin/gestionar_ventas', 'Admin::gestionar_ventas');

// Administración: consultas
$routes->get('admin/gestionar_consultas', 'Admin::gestionar_consultas');
$routes->get('admin/marcar_leida/(:num)', 'Admin::marcar_leida/$1');
$routes->get('admin/marcar_no_leida/(:num)', 'Admin::marcar_no_leida/$1');
