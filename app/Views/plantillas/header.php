<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1Kamisetas - Store</title>
    <link href="assets/css/header.css" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
</head>

<body>
    <header> 
    <a href="principal" class="logo">
        <img src="assets/img/logo.png" alt="Logo" class="logo-img">           
    </a>

    <a class="miltext">
        <img src="assets/img/miltext.png" alt="">
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

    <button class="login-btn"><a href="login">Iniciar sesión</a></button>

    <!-- Botón menú hamburguesa para móvil -->
    <div class="menu-toggle" id="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>
</header>

<!-- Menú lateral oculto -->
<div class="side-menu" id="side-menu">
    <a href="#" class="nav-item">Catálogo</a>
    <a href="comercio" class="nav-item">Comercialización</a>
    <a href="nosotros" class="nav-item">Nosotros</a>
    <a href="login" class="mobile-login">Iniciar sesión</a>

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
    <a href="#" class="nav-item">Catálogo</a>
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

<script>
function toggleMenu() {
    const menu = document.getElementById("mobileMenu");
    menu.classList.toggle("show");
}
</script>
    
    
    
</body> 
</html>

