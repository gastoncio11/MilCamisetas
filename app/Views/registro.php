<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/registro.css">
</head>
<body>

    <main class="registro-background">
        <div class="registro-container">
            <form class="registro-form" method="post" action="procesar_registro.php">
                <h2>Crear Cuenta</h2>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required>

                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required>

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>

                <label for="contraseña">Contraseña</label>
                <input type="contraseña" id="contraseña" name="contraseña" required>

                <label for="club">Club favorito</label>
                <input type="text" id="club" name="club" required>

                <div class="checkbox-group">
                    <input type="checkbox" id="suscripcion" name="suscripcion" value="1">
                    <label for="suscripcion">¿Desea recibir correos cuando haya remeras nuevas de su club favorito?</label>
                </div>

                <button type="submit">Registrarse</button>
                <p class="iniciar">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí.</a></p>
            </form>
        </div>
    </main>


</body>
</html>
