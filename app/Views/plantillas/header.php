<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'MilCamisetas' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/principal.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/terminosdeuso.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nosotros.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/contacto.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/comercio.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/catalogo.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/producto.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">


</head>

<body>
    <header> 
    <a href="<?= base_url('principal') ?>" class="logo">
        <img src="<?= base_url('assets/img/logo.png') ?>"  alt="Logo" class="logo-img">           
    </a>

    <div class="contenedor-red">
        <a href="https://www.instagram.com/gas11_ton11/" target="_blank">
            <img src="<?= base_url('assets/img/instagram.png') ?>"  alt="Logo IG" class="logo-red">  
        </a>
        <a href="https://www.facebook.com/hugo.elias.333786" target="_blank">
            <img src="<?= base_url('assets/img/facebook.png') ?>"  alt="Logo FB" class="logo-red">
        </a>
        <a href="https://x.com/Argentina" target="_blank">
            <img src="<?= base_url('assets/img/twitter.png') ?>"  alt="Logo TW" class="logo-red">
        </a>
    </div>

    <div class="contenedorSesion">
         <a href="<?= base_url('login') ?>" class="btn-sesion">Iniciar sesión</a>
         <p>o</p>
         <a href="<?= base_url('registro') ?>" class="btn-sesion">Registrarse</a>
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
    <a href="<?= base_url('catalogo') ?>" class="nav-item">Catálogo</a>
    <a href="<?= base_url('comercio') ?>" class="nav-item">Comercialización</a>
    <a href="<?= base_url('nosotros') ?>" class="nav-item">Nosotros</a>
    <a href="<?= base_url('login') ?>" class="mobile-login">Iniciar sesión</a>
    <a href="<?= base_url('registro') ?>" class="mobile-login">Registrarse</a>

    <br>
    <br>
    
       <h1>Nuestras redes!</h1>
    <div class="social-links">
        <a href="https://www.instagram.com/gas11_ton11/" target="_blank" class="social-item">
            <img src="<?= base_url('assets/img/instagram.png') ?>"  alt="Logo IG" class="logo-red">    
        </a>
        <a href="https://www.facebook.com/hugo.elias.333786" target="_blank" class="social-item">
            <img src="<?= base_url('assets/img/facebook.png') ?>"  alt="Logo FB" class="logo-red">  
        </a>
        <a href="https://x.com/Argentina" target="_blank" class="social-item">
            <img src="<?= base_url('assets/img/twitter.png') ?>"  alt="Logo TW" class="logo-red">  
        </a>
    </div>

</div>

<!-- Overlay oscuro -->
<div class="overlay" id="overlay"></div>

<!-- Navbar normal -->
<nav class="contenedor">
    <a href="<?= base_url('catalogo') ?>" class="nav-item">Catálogo</a>
    <a href="<?= base_url('comercio') ?>" class="nav-item">Comercialización</a>
    <a href="<?= base_url('nosotros') ?>" class="nav-item">Nosotros</a>
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

<script>
function toggleMenu() {
    const menu = document.getElementById("mobileMenu");
    menu.classList.toggle("show");
}
</script>

