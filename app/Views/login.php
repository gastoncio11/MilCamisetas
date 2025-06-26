<main class="login-background">
    <div class="login-container">
        <!-- Mostrar mensajes de éxito o error -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="post" action="<?= base_url('usuario/login') ?>">
            <?= csrf_field() ?>
            <h2>Iniciar Sesión</h2>

            <!-- Campo Email -->
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="ejemplo@correo.com"
                       value="<?= old('email') ?>" 
                       class="<?= (isset($validation) && $validation->hasError('email')) ? 'error' : '' ?>"
                       >
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <div class="error-message">
                        <?= $validation->getError('email') ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="contraseña">Contraseña</label>
                <input type="password" 
                       id="contraseña" 
                       name="contraseña" 
                       placeholder="Tu contraseña"
                       class="<?= (isset($validation) && $validation->hasError('contraseña')) ? 'error' : '' ?>"
                       >
                <?php if (isset($validation) && $validation->hasError('contraseña')): ?>
                    <div class="error-message">
                        <?= $validation->getError('contraseña') ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit">Iniciar Sesión</button>
            <p class="registro">¿No tienes cuenta? <a href="<?= base_url('registro') ?>">Regístrate aquí.</a></p>
        </form>
    </div>
</main>