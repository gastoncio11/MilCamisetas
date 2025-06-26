<div>
    <div class="perfil-container" style="margin-top: 40px;">
    
        <!-- Mostrar mensajes de éxito -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="icon-check">✓</i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar mensajes de error -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <i class="icon-error">✗</i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar errores de validación individuales -->
        <?php if (isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-error">
                <i class="icon-error">✗</i>
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="error-list">
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
                                <input type="text" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder="Ingresa tu nombre"
                                       value="<?= old('nombre', $usuario['nombre']) ?>" 
                                       class="<?= (isset($validation) && $validation->hasError('nombre')) ? 'error' : '' ?>"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                                    <div class="field-error">
                                        <?= $validation->getError('nombre') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" 
                                       id="apellido" 
                                       name="apellido" 
                                       placeholder="Ingresa tu apellido"
                                       value="<?= old('apellido', $usuario['apellido']) ?>" 
                                       class="<?= (isset($validation) && $validation->hasError('apellido')) ? 'error' : '' ?>"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('apellido')): ?>
                                    <div class="field-error">
                                        <?= $validation->getError('apellido') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   placeholder="ejemplo@correo.com"
                                   value="<?= old('email', $usuario['email']) ?>" 
                                   class="<?= (isset($validation) && $validation->hasError('email')) ? 'error' : '' ?>"
                                   required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <div class="field-error">
                                    <?= $validation->getError('email') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Cambiar Contraseña</h3>
                        <p class="section-description">Deja estos campos vacíos si no deseas cambiar tu contraseña</p>
                        
                        <div class="form-group">
                            <label for="contraseña_actual">Contraseña Actual</label>
                            <input type="password" 
                                   id="contraseña_actual" 
                                   name="contraseña_actual"
                                   placeholder="Tu contraseña actual"
                                   class="<?= (isset($validation) && $validation->hasError('contraseña_actual')) ? 'error' : '' ?>">
                            <?php if (isset($validation) && $validation->hasError('contraseña_actual')): ?>
                                <div class="field-error">
                                    <?= $validation->getError('contraseña_actual') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nueva_contraseña">Nueva Contraseña</label>
                                <input type="password" 
                                       id="nueva_contraseña" 
                                       name="nueva_contraseña"
                                       placeholder="Mínimo 6 caracteres"
                                       class="<?= (isset($validation) && $validation->hasError('nueva_contraseña')) ? 'error' : '' ?>">
                                <?php if (isset($validation) && $validation->hasError('nueva_contraseña')): ?>
                                    <div class="field-error">
                                        <?= $validation->getError('nueva_contraseña') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmar_nueva_contraseña">Confirmar Nueva Contraseña</label>
                                <input type="password" 
                                       id="confirmar_nueva_contraseña" 
                                       name="confirmar_nueva_contraseña"
                                       placeholder="Repite la nueva contraseña"
                                       class="<?= (isset($validation) && $validation->hasError('confirmar_nueva_contraseña')) ? 'error' : '' ?>">
                                <?php if (isset($validation) && $validation->hasError('confirmar_nueva_contraseña')): ?>
                                    <div class="field-error">
                                        <?= $validation->getError('confirmar_nueva_contraseña') ?>
                                    </div>
                                <?php endif; ?>
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
                                </div>