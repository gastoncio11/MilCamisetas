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
$routes->get('catalogo', 'Home::catalogo');
$routes->get('producto/(:num)', 'Home::producto/$1');
$routes->get('login', 'Home::login');
$routes->get('registro', 'Home::registro');
$routes->post('registro', 'Home::registro');
$routes->get('carrito', 'Carrito_controller::ver_carrito');
$routes->post('agregar-carrito', 'Carrito_controller::agregar_carrito');
$routes->post('guardar-venta', 'VentaController::guardar_venta');
