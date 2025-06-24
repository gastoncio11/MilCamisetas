<main class>
    <div class="perfil-container">
        <div class="perfil-header">
            <h1>Mi Perfil</h1>
            <p>Gestiona tu información personal</p>
        </div>

        <!-- Mostrar mensajes -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="icon-check"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="icon-error"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-error">
                <i class="icon-error"></i>
                <ul>
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="perfil-content">
            <!-- Información del usuario -->
            <div class="perfil-info">
                <div class="user-avatar">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)) ?>
                    </div>
                </div>
                <div class="user-details">
                    <h2><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h2>
                    <p class="user-email"><?= esc($usuario['email']) ?></p>
                    <p class="user-type">
                        <?= $usuario['perfil_id'] == 1 ? 'Administrador' : 'Cliente' ?>
                    </p>
                </div>
            </div>

            <!-- Formulario de edición -->
            <div class="perfil-form">
                <form method="post" action="<?= base_url('usuario/actualizar_perfil') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="form-section">
                        <h3>Información Personal</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" 
                                       value="<?= isset($validation) ? set_value('nombre') : esc($usuario['nombre']) ?>" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" 
                                       value="<?= isset($validation) ? set_value('apellido') : esc($usuario['apellido']) ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= isset($validation) ? set_value('email') : esc($usuario['email']) ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Cambiar Contraseña</h3>
                        <p class="section-description">Deja estos campos vacíos si no deseas cambiar tu contraseña</p>
                        
                        <div class="form-group">
                            <label for="contraseña_actual">Contraseña Actual</label>
                            <input type="password" id="contraseña_actual" name="contraseña_actual">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nueva_contraseña">Nueva Contraseña</label>
                                <input type="password" id="nueva_contraseña" name="nueva_contraseña">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmar_nueva_contraseña">Confirmar Nueva Contraseña</label>
                                <input type="password" id="confirmar_nueva_contraseña" name="confirmar_nueva_contraseña">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                        <a href="<?= base_url('principal') ?>" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>