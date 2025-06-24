<main class="registro-background">
    <div class="registro-container">
        
        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($success)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #c3e6cb;">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar error general si existe -->
        <?php if (isset($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar errores de validación -->
        <?php if (isset($validation)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="registro-form" method="post" action="<?= base_url('usuario/registrar') ?>">
            <?= csrf_field() ?>
            <h2>Crear Cuenta</h2>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= isset($validation) ? set_value('nombre') : '' ?>" required>

            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" 
                   value="<?= isset($validation) ? set_value('apellido') : '' ?>" required>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" 
                   value="<?= isset($validation) ? set_value('email') : '' ?>" required>

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <label for="confirmar_contraseña">Repetir contraseña</label>
            <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required>

            <button type="submit">Registrarse</button>
            <p class="iniciar">¿Ya tienes cuenta? <a href="<?= base_url('login') ?>">Inicia sesión aquí.</a></p>
        </form>
    </div>
</main>