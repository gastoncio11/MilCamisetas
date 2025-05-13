<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>



    <!-- CONTENIDO PRINCIPAL -->
    <main class="login-background">
        <div class="login-container">
            <form class="login-form" method="post" action="procesar_login.php">
                <h2>Iniciar Sesión</h2>

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Ingresar</button>
                <p class="registrarse">¿No tienes cuenta aún? <a href="registro">Registrate aquí.</a></p>
            </form>
        </div>
    </main>



</body>
</html>
