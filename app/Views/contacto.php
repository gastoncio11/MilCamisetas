<div class="contactanos">
    <!-- SECCIÓN SUPERIOR -->
    <section id="c-hero">
        <div class="contacto">
            <h1>Contactate con nosotros!</h1>
            <p>Lunes a Viernes</p>
            <p>8:00 - 12:00 / 16:00 - 21:00</p>
        </div>
    </section>

    <!-- CORREOS Y FORMULARIO -->
    <section id="c-hero3">
        <div class="correos">
            <div class="contenedor-correo">
                <a href="mailto:gastonfernandez2015.gf@gmail.com" target="_blank">
                    <img src="assets/img/gmail.png" alt="logo correo" class="logo-correo">  
                </a>
                <p>gastonfernandez2015.gf@gmail.com</p>
            </div>
            <div class="contenedor-correo">
                <a href="mailto:hugoelias687@gmail.com" target="_blank">
                    <img src="assets/img/gmail.png" alt="logo correo" class="logo-correo">  
                </a>
                <p>hugoelias687@gmail.com</p>
            </div>
            <div class="contenedor-correo">
                <a href="https://wa.me/5493794523111" target="_blank">
                    <img src="assets/img/WhatsApp.png" alt="logo whatsapp" class="logo-wp">  
                </a>
                <p>+54 9 379-4-523111</p>
            </div>
        </div>

        <!-- FORMULARIO DE CONSULTA -->
        <div class="formulario">
            <div class="carta-personalizada">
                <div class="carta-body">
                    <h5 class="carta-title">FORMULARIO DE CONTACTO</h5>
                    <p class="carta-texto">Deja tus consultas</p>

                    <!-- Mostrar mensajes de éxito -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alerta-exito">
                            <span class="alerta-icon">✓</span>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Mostrar mensajes de error general -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alerta-error">
                            <span class="alerta-icon">✗</span>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Mostrar errores de validación -->
                    <?php if (session()->getFlashdata('validation')): ?>
                        <div class="alerta-error">
                            <span class="alerta-icon">⚠️</span>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="lista-errores">
                                <?php foreach (session()->getFlashdata('validation')->getErrors() as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('guardar_consulta') ?>">
                        <?= csrf_field() ?>
                        
                        <!-- Fila: Nombre y Apellido -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" 
                                       name="nombre" 
                                       placeholder="Nombre *" 
                                       value="<?= old('nombre') ?>"
                                       class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('nombre')) ? 'campo-error' : '' ?>"
                                       >
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('nombre')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('nombre') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="campo">
                                <input type="text" 
                                       name="apellido" 
                                       placeholder="Apellido *" 
                                       value="<?= old('apellido') ?>"
                                       class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('apellido')) ? 'campo-error' : '' ?>"
                                       >
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('apellido')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('apellido') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fila: Email -->
                        <div class="fila">
                            <div class="campo">
                                <input type="email" 
                                       name="email" 
                                       placeholder="Correo electrónico *" 
                                       value="<?= old('email') ?>"
                                       class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('email')) ? 'campo-error' : '' ?>"
                                       >
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('email')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('email') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fila: Teléfono -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" 
                                       name="telefono" 
                                       placeholder="Teléfono *" 
                                       value="<?= old('telefono') ?>"
                                       class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('telefono')) ? 'campo-error' : '' ?>"
                                       >
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('telefono')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('telefono') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fila: Asunto -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" 
                                       name="asunto" 
                                       placeholder="Asunto *" 
                                       value="<?= old('asunto') ?>"
                                       class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('asunto')) ? 'campo-error' : '' ?>"
                                       >
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('asunto')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('asunto') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Fila: Mensaje -->
                        <div class="fila">
                            <div class="campo">
                                <textarea name="mensaje" 
                                          placeholder="Escribe tu mensaje aquí... *" 
                                          class="<?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('mensaje')) ? 'campo-error' : '' ?>"
                                          ><?= old('mensaje') ?></textarea>
                                <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('mensaje')): ?>
                                    <div class="error-campo">
                                        <?= session()->getFlashdata('validation')->getError('mensaje') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Información de campos obligatorios -->
                        <div class="fila">
                            <p class="campos-obligatorios">* Campos obligatorios</p>
                        </div>

                        <!-- Botón -->
                        <div class="fila">
                            <button type="submit" class="btn-enviar">
                                <span class="btn-texto">Enviar Consulta</span>
                                <span class="btn-icon">📧</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- DIRECCIÓN -->
    <section id="c-hero2">
        <div class="direccion">
            <h1>Dirección</h1>
            <p>La Pampa 1569, Corrientes Argentina</p>
        </div>
        <div class="btn-mapa-container">
            <a href="https://www.google.com/maps/place/La+Pampa+1569,+Corrientes,+Argentina" target="_blank" class="btn-mapa">
                Ver en Google Maps
            </a>
        </div>
    </section>

    <!-- DATOS LEGALES -->
    <section id="c-hero4">
        <div class="legal">
            <h1>Datos Legales</h1>
            <p>Razón Social: 1Kamisetas</p>
            <p>Titular: Elias Hugo</p>
            <p>CUIT: 29-98765432-1</p>
            <p>Inscripción AFIP: Activo</p>
            <p>Domicilio Fiscal: La Pampa 1569, Corrientes</p>
            <p>Actividad Económica: Venta de indumentaria deportiva</p>
            <p>Tipo de Contribuyente: Responsable Inscripto</p>
        </div>
    </section>
</div>