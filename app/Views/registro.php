

    <title>1Kamisetas - Registro </title>
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
                <input type="password" id="contraseña" name="contraseña" required>
<<<<<<< HEAD
=======

                <label for="contraseña">Repetir contraseña</label>
                <input type="password" id="contraseña" name="contraseña" required>
>>>>>>> ec95d7d4fe07da1c0229061270e5af1a195592be

                <label for="contraseña">Repetir contraseña</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <div class="checkbox-group">
                    <input type="checkbox" id="suscripcion" name="suscripcion" value="1">
                    <label for="suscripcion">¿Desea recibir correos cuando haya ofertas y descuentos?</label>
                </div>

                <button type="submit">Registrarse</button>
                <p class="iniciar">¿Ya tienes cuenta? <a href="login">Inicia sesión aquí.</a></p>
            </form>
        </div>
    </main>
