<main class="login-background">
    <div class="login-container">
        <!-- Mostrar mensajes de éxito o error -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar errores de validación -->
        <?php if (session()->get('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="login-form" method="post" action="<?= base_url('usuario/login') ?>">
            <?= csrf_field() ?>
            <h2>Iniciar Sesión</h2>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required>

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <button type="submit">Iniciar Sesión</button>
            <p class="registro">¿No tienes cuenta? <a href="<?= base_url('registro') ?>">Regístrate aquí.</a></p>
        </form>
    </div>
</main>