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
                <a href="#" target="_blank">
                    <img src="assets/img/WhatsApp.png" alt="logo correo" class="logo-wp">  
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
                    <form method="post" action="<?= base_url('guardar_consulta') ?>">
                        <!-- Fila: Nombre y Apellido -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" name="nombre" placeholder="Nombre" required>
                            </div>
                            <div class="campo">
                                <input type="text" name="apellido" placeholder="Apellido" required>
                            </div>
                        </div>

                        <!-- Fila: Email -->
                        <div class="fila">
                            <div class="campo">
                                <input type="email" name="email" placeholder="Correo electrónico" required>
                            </div>
                        </div>

                        <!-- Fila: Teléfono -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" name="telefono" placeholder="Teléfono">
                            </div>
                        </div>

                        <!-- Fila: Asunto -->
                        <div class="fila">
                            <div class="campo">
                                <input type="text" name="asunto" placeholder="Asunto">
                            </div>
                        </div>

                        <!-- Fila: Mensaje -->
                        <div class="fila">
                            <div class="campo">
                                <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="fila">
                            <button type="submit" class="btn-enviar">Enviar</button>
                        </div>
                    </form>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alerta-exito">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

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
