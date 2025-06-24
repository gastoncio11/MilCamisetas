<div class="producto">
    <section id="producto-detalle">
        <div class="producto-container">

            <!-- Imagen del producto -->
            <div class="producto-imagen">
                <img src="<?= base_url('assets/img/' . trim($producto['imagen'])) ?>" alt="<?= esc($producto['nombre']) ?>">
            </div>

            <!-- Información del producto -->
            <div class="producto-info">
                <h1><?= esc($producto['nombre']) ?></h1>
                <p class="precio-principal">$<?= number_format($producto['precio'], 2, ',', '.') ?></p>
                <p class="ver-medios">VER MEDIOS DE PAGO</p>

                <!-- Talles disponibles -->
                <form action="<?= base_url('agregar-carrito') ?>" method="post" id="formCarrito">
                    <!-- Datos ocultos necesarios -->
                    <input type="hidden" name="id" value="<?= esc($producto['id']) ?>">
                    <input type="hidden" name="titulo" value="<?= esc($producto['nombre']) ?>">
                    <input type="hidden" name="precio" value="<?= esc($producto['precio']) ?>">
                    <input type="hidden" name="talle" id="inputTalle">
                    <input type="hidden" name="cantidad" id="inputCantidad">

                    <!-- Talles disponibles -->
                    <div class="talles">
                        <label for="talle">TALLE:</label>
                        <div class="talles-opciones">
                            <?php foreach ($producto['talles'] as $talle): ?>
                                <button type="button" class="talle-btn"><?= esc($talle) ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="cantidad">
                        <label for="cantidad">CANTIDAD</label>
                        <input type="number" id="cantidadInput" value="1" min="1">
                    </div>

                    <!-- Botón carrito -->
                    <button type="submit" class="boton-carrito">AGREGAR AL CARRITO</button>
                </form>


                <!-- Compra protegida -->
                <div class="proteccion">
                    <strong>Compra protegida</strong><br>
                    Tus datos cuidados durante toda la compra.
                </div>
            </div>

        </div>
    </section>
</div>

    <script>
        const botonesTalle = document.querySelectorAll('.talle-btn');
        const inputTalle = document.getElementById('inputTalle');
        const inputCantidad = document.getElementById('inputCantidad');
        const cantidadInput = document.getElementById('cantidadInput');
        const form = document.getElementById('formCarrito');

        let talleSeleccionado = null;

        botonesTalle.forEach(btn => {
            btn.addEventListener('click', () => {
                botonesTalle.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                talleSeleccionado = btn.textContent;
            });
        });

        form.addEventListener('submit', function(e) {
            if (!talleSeleccionado) {
                e.preventDefault();
                alert('Por favor seleccioná un talle');
                return;
            }

            inputTalle.value = talleSeleccionado;
            inputCantidad.value = cantidadInput.value;
        });
    </script>

