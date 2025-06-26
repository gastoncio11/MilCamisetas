<?php
// Obtener información del usuario logueado si existe
$session = session();
$usuario_logueado = $session->get('usuario_logueado');
$perfil_id = $session->get('perfil_id');
$nombre_usuario = $session->get('nombre_usuario');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'MilCamisetas' ?></title>
    <base href="<?= base_url() ?>">
    
    <link href="<?= base_url('assets/css/header.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/registro.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/login.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/principal.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/footer.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/terminosdeuso.css') ?>" rel="stylesheet">
     <link href="<?= base_url('assets/css/operaciones.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/gestionar_productos.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-consultas.css') ?>" rel="stylesheet">
     <link href="<?= base_url('assets/css/nosotros.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/gestionar_usuarios.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/perfil.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/contacto.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/carrito.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/catalogo.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/comercio.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/producto.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/gestionar_ventas.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/gestionar_consulta.css') ?>" rel="stylesheet">
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="principal" class="logo">
            <img src="assets/img/logo.png" alt="Logo" class="logo-img">
        </a>
        
        <div class="contenedor-red">
            <a href="https://www.instagram.com/gas11_ton11/" target="_blank">
                <img src="assets/img/instagram.png" alt="Logo IG" class="logo-red">
            </a>
            <a href="https://www.facebook.com/hugo.elias.333786" target="_blank">
                <img src="assets/img/facebook.png" alt="Logo FB" class="logo-red">
            </a>
            <a href="https://x.com/Argentina" target="_blank">
                <img src="assets/img/twitter.png" alt="Logo TW" class="logo-red">
            </a>
        </div>
        
        <div class="auth-buttons">
            <?php if (!$usuario_logueado): ?>
                <!-- Usuario NO logueado -->
                <button class="login-btn"><a href="login">Iniciar sesión</a></button>
                <button class="register-btn"><a href="registro">Registrarse</a></button>
            
            <?php elseif ($perfil_id == 0): ?>
                <!-- Usuario CLIENTE logueado -->
                <span class="welcome-text">Hola, <?= esc($nombre_usuario) ?></span>
                <a href="perfil" class="icon-btn profile-btn" title="Ver Perfil">
                    <img src="assets/img/perfil-icon.png" alt="Perfil" class="btn-icon">
                </a>
                <a href="carrito" class="icon-btn cart-btn" title="Ver Carrito">
                    <img src="assets/img/carrito-icon.png" alt="Carrito" class="btn-icon">
                </a>
                <a href="logout" class="icon-btn logout-btn" title="Cerrar Sesión">
                    <img src="assets/img/logout-icon.png" alt="Cerrar Sesión" class="btn-icon">
                </a>
            
            <?php elseif ($perfil_id == 1): ?>
                <!-- Usuario ADMINISTRADOR logueado -->
                <span class="welcome-text">Admin: <?= esc($nombre_usuario) ?></span>
                <a href="operaciones" class="icon-btn admin-btn" title="Operaciones">
                    <img src="assets/img/admin-icon.png" alt="Operaciones" class="btn-icon">
                </a>
                <a href="perfil" class="icon-btn profile-btn" title="Ver Perfil">
                    <img src="assets/img/perfil-icon.png" alt="Perfil" class="btn-icon">
                </a>
                <a href="logout" class="icon-btn logout-btn" title="Cerrar Sesión">
                    <img src="assets/img/logout-icon.png" alt="Cerrar Sesión" class="btn-icon">
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Botón menú hamburguesa para móvil -->
        <div class="menu-toggle" id="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </header>

    <!-- Menú lateral oculto -->
    <div class="side-menu" id="side-menu">
        <?php if ($perfil_id != 1): ?>
            <!-- Mostrar catálogo solo si NO es administrador -->
            <a href="catalogo" class="nav-item">Catálogo</a>
        <?php endif; ?>
        
        <?php if ($perfil_id == 1): ?>
            <!-- Opciones específicas para administrador -->
            <a href="operaciones" class="nav-item">Operaciones</a>
        <?php endif; ?>
        
        <a href="comercio" class="nav-item">Comercialización</a>
        <a href="nosotros" class="nav-item">Nosotros</a>
        
        <div class="mobile-auth-buttons">
            <?php if (!$usuario_logueado): ?>
                <!-- Usuario NO logueado - móvil -->
                <a href="login" class="mobile-login">Iniciar sesión</a>
                <a href="registro" class="mobile-register">Registrarse</a>
            
            <?php elseif ($perfil_id == 0): ?>
                <!-- Usuario CLIENTE logueado - móvil -->
                <div class="mobile-user-info">Hola, <?= esc($nombre_usuario) ?></div>
                <a href="perfil" class="mobile-profile">Ver Perfil</a>
                <a href="carrito" class="mobile-cart">Ver Carrito</a>
                <a href="logout" class="mobile-logout">Cerrar Sesión</a>
            
            <?php elseif ($perfil_id == 1): ?>
                <!-- Usuario ADMINISTRADOR logueado - móvil -->
                <div class="mobile-user-info">Admin: <?= esc($nombre_usuario) ?></div>
                <a href="operaciones" class="mobile-admin">Operaciones</a>
                <a href="perfil" class="mobile-profile">Ver Perfil</a>
                <a href="logout" class="mobile-logout">Cerrar Sesión</a>
            <?php endif; ?>
        </div>
        
        <br>
        <br>
        <h1>Nuestras redes!</h1>
        <div class="social-links">
            <a href="https://www.instagram.com/gas11_ton11/" target="_blank" class="social-item">
                <img src="assets/img/instagram.png" alt="Logo IG" class="logo-red">
            </a>
            <a href="https://www.facebook.com/hugo.elias.333786" target="_blank" class="social-item">
                <img src="assets/img/facebook.png" alt="Logo FB" class="logo-red">
            </a>
            <a href="https://x.com/Argentina" target="_blank" class="social-item">
                <img src="assets/img/twitter.png" alt="Logo TW" class="logo-red">
            </a>
        </div>
    </div>

    <!-- Overlay oscuro -->
    <div class="overlay" id="overlay"></div>

    <!-- Navbar normal -->
    <nav class="contenedor">
        <?php if ($perfil_id != 1): ?>
            <!-- Mostrar catálogo solo si NO es administrador -->
            <a href="catalogo" class="nav-item">Catálogo</a>
        <?php endif; ?>
        
        <?php if ($perfil_id == 1): ?>
            <!-- Opciones específicas para administrador -->
            <a href="operaciones" class="nav-item">Operaciones</a>
        <?php endif; ?>
        
        <a href="comercio" class="nav-item">Comercialización</a>
        <a href="nosotros" class="nav-item">Nosotros</a>
    </nav>

    <!-- JavaScript -->
    <script>
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('side-menu');
        const overlay = document.getElementById('overlay');
        
        toggle.addEventListener('click', () => {
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', () => {
            menu.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>