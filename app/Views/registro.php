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

    <form class="registro-form" method="post" action="<?= base_url('usuario/registrar') ?>">
    <?= csrf_field() ?>
    <h2>Crear Cuenta</h2>

    <!-- Mostrar errores generales -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <span class="alert-icon">✗</span>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Mostrar mensaje de éxito -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Campo Nombre -->
    <div class="form-group">
        <label for="nombre" style="color:rgb(189, 189, 189);">Nombre</label>
        <input type="text" 
               id="nombre" 
               name="nombre" 
               placeholder="Ingresa tu nombre"
               value="<?= (isset($validation) && $validation->hasError('nombre')) ? '' : (isset($inputData['nombre']) ? esc($inputData['nombre']) : old('nombre')) ?>" 
               class="<?= (isset($validation) && $validation->hasError('nombre')) ? 'error' : '' ?>"
               >
        <?php if (isset($validation) && $validation->hasError('nombre')): ?>
            <div class="error-message">
                <?= $validation->getError('nombre') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Campo Apellido -->
    <div class="form-group">
        <label for="apellido" style="color:rgb(189, 189, 189);">Apellido</label>
        <input type="text" 
               id="apellido" 
               name="apellido" 
               placeholder="Ingresa tu apellido"
               value="<?= (isset($validation) && $validation->hasError('apellido')) ? '' : (isset($inputData['apellido']) ? esc($inputData['apellido']) : old('apellido')) ?>" 
               class="<?= (isset($validation) && $validation->hasError('apellido')) ? 'error' : '' ?>"
               >
        <?php if (isset($validation) && $validation->hasError('apellido')): ?>
            <div class="error-message">
                <?= $validation->getError('apellido') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Campo Email -->
    <div class="form-group">
        <label for="email" style="color:rgb(189, 189, 189);">Correo electrónico</label>
        <input type="email" 
               id="email" 
               name="email" 
               placeholder="ejemplo@correo.com"
               value="<?= (isset($validation) && $validation->hasError('email')) ? '' : (isset($inputData['email']) ? esc($inputData['email']) : old('email')) ?>" 
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
        <label for="contraseña" style="color:rgb(189, 189, 189);">Contraseña</label>
        <input type="password" 
               id="contraseña" 
               name="contraseña" 
               placeholder="Mínimo 6 caracteres"
               value=""
               class="<?= (isset($validation) && $validation->hasError('contraseña')) ? 'error' : '' ?>"
               >
        <?php if (isset($validation) && $validation->hasError('contraseña')): ?>
            <div class="error-message">
                <?= $validation->getError('contraseña') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Campo Confirmar Contraseña -->
    <div class="form-group">
        <label for="confirmar_contraseña" style="color:rgb(189, 189, 189);">Repetir contraseña</label>
        <input type="password" 
               id="confirmar_contraseña" 
               name="confirmar_contraseña" 
               placeholder="Repite tu contraseña"
               value=""
               class="<?= (isset($validation) && $validation->hasError('confirmar_contraseña')) ? 'error' : '' ?>"
               >
        <?php if (isset($validation) && $validation->hasError('confirmar_contraseña')): ?>
            <div class="error-message">
                <?= $validation->getError('confirmar_contraseña') ?>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit">Registrarse</button>
    <p class="iniciar">¿Ya tienes cuenta? <a href="<?= base_url('login') ?>">Inicia sesión aquí.</a></p>
</form>
    </div>
</main>

<style>
/* Estilos para campos con error */
.form-group input.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    background-color: #fff5f5;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

/* Animación suave para campos con error */
.form-group input.error {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Estilos para alertas */
.alert {
    padding: 12px 16px;
    margin-bottom: 16px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-icon {
    font-weight: bold;
    font-size: 1.1em;
}

/* Mejorar la apariencia del formulario */
.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #fff;
}

.form-group input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Placeholder mejorado */
.form-group input::placeholder {
    color: #6c757d;
    opacity: 0.8;
}

/* Botón de envío mejorado */
button[type="submit"] {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 1rem;
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

button[type="submit"]:active {
    transform: translateY(0);
}
</style>

<script>
// Mejorar la experiencia del usuario
document.addEventListener('DOMContentLoaded', function() {
    // Enfocar el primer campo con error
    const firstErrorField = document.querySelector('.form-group input.error');
    if (firstErrorField) {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Limpiar estilos de error cuando el usuario empiece a escribir
    const inputs = document.querySelectorAll('.form-group input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                this.classList.remove('error');
                const errorMessage = this.parentNode.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.opacity = '0.5';
                }
            }
        });
        
        // Restaurar error si el campo queda vacío y tenía error
        input.addEventListener('blur', function() {
            const errorMessage = this.parentNode.querySelector('.error-message');
            if (errorMessage && this.value.trim() === '') {
                this.classList.add('error');
                errorMessage.style.opacity = '1';
            }
        });
    });
    
    // Validación en tiempo real para contraseñas
    const password = document.getElementById('contraseña');
    const confirmPassword = document.getElementById('confirmar_contraseña');
    
    if (password && confirmPassword) {
        function validatePasswords() {
            if (password.value && confirmPassword.value) {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        }
        
        password.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
    }
});
</script>
