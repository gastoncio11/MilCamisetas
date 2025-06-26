
<main style="transparent">
    <div class="container">
        <div class="producto-container">
            <!-- Imagen del producto -->
            <div class="producto-imagen-container">
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="<?= base_url('assets/img/productos/' . $producto['imagen']) ?>" 
                         alt="<?= esc($producto['nombre']) ?>" 
                         class="producto-imagen-principal">
                <?php else: ?>
                    <div class="imagen-placeholder">
                        <span>📷</span>
                        <p>Sin imagen disponible</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Información del producto -->
            <div class="producto-info-container">
                <div class="producto-header">
                    <h1 class="producto-titulo"><?= esc($producto['nombre']) ?></h1>
                    <p class="producto-precio">$<?= number_format($producto['precio'], 2) ?></p>
                </div>

                <div class="producto-descripcion">
                    <h3>Descripción</h3>
                    <p><?= esc($producto['descripcion']) ?></p>
                </div>

                <!-- Selección de talle -->
                <div class="producto-talles">
                    <h3>Seleccionar Talle</h3>
                    <div class="talles-grid" id="tallesGrid">
                        <?php if (!empty($producto['stock_detalle'])): ?>
                            <?php foreach ($producto['stock_detalle'] as $stock): ?>
                                <button type="button"
                                    class="talle-btn <?= $stock['stock'] > 0 ? 'disponible' : 'agotado' ?>"
                                        data-talle="<?= $stock['talle'] ?>"
                                        data-stock="<?= $stock['stock'] ?>"
                                        <?= $stock['stock'] <= 0 ? 'disabled' : '' ?>
                                        onclick="seleccionarTalle('<?= $stock['talle'] ?>', <?= $stock['stock'] ?>)">
                                    <?= $stock['talle'] ?>
                                    <span class="stock-info">(<?= $stock['stock'] ?> disponibles)</span>
                                </button>
                            <?php endforeach; ?>
                        <?php elseif (!empty($producto['talles'])): ?>
                            <?php foreach ($producto['talles'] as $talle): ?>
                                <button type="button" 
                                        class="talle-btn disponible" 
                                        data-talle="<?= $talle ?>"
                                        onclick="seleccionarTalle('<?= $talle ?>', 999)">
                                    <?= $talle ?>
                                </button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="sin-talles">No hay talles disponibles</p>
                        <?php endif; ?>
                    </div>
                    <p class="talle-seleccionado" id="talleSeleccionado" style="display: none;">
                        Talle seleccionado: <strong id="talleActual"></strong>
                    </p>
                </div>
                <form id="formAgregarCarrito" method="post" action="<?= base_url('agregar-carrito') ?>">
                    <input type="hidden" name="id" value="<?= esc($producto['id']) ?>">
                    <input type="hidden" name="titulo" value="<?= esc($producto['nombre']) ?>">
                    <input type="hidden" name="precio" value="<?= esc($producto['precio']) ?>">
                    <input type="hidden" name="talle" id="inputTalle">
                    <input type="hidden" name="cantidad" id="inputCantidad">

                    <div class="form-flex">
                        <div class="cantidad-selector">
                            <label for="cantidad">Cantidad:</label>
                            <div class="cantidad-controls">
                                <button type="button" onclick="cambiarCantidad(-1)" class="cantidad-btn">-</button>
                                <input type="number" id="cantidad" value="1" min="1" max="1" readonly>
                                <button type="button" onclick="cambiarCantidad(1)" class="cantidad-btn">+</button>
                            </div>
                        </div>

                        <button type="submit" id="btnAgregarCarrito" class="btn-agregar-carrito" disabled>
                            🛒 Agregar al Carrito
                        </button>
                    </div>
                </form>


                
                <?php 
                $stockTotal = 0;
                if (!empty($producto['stock_detalle'])) {
                    $stockTotal = array_sum(array_column($producto['stock_detalle'], 'stock'));
                }
                ?>
                <div class="stock-info-total">
                    <p class="stock-disponible">
                        <strong>Stock total disponible: <?= $stockTotal ?> unidades</strong>
                    </p>
                </div>

                <!-- Botón volver -->
                <div class="producto-acciones">
                    <a href="<?= base_url('catalogo') ?>" class="btn-volver">
                        ← Volver al Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    let talleSeleccionado = '';
    let stockDisponible = 1;

    function seleccionarTalle(talle, stock) {
        document.querySelectorAll('.talle-btn').forEach(btn => {
            btn.classList.remove('seleccionado');
        });

        const btnTalle = document.querySelector(`[data-talle="${talle}"]`);
        if (btnTalle) {
            btnTalle.classList.add('seleccionado');
        }

        talleSeleccionado = talle;
        stockDisponible = stock;

        document.getElementById('talleSeleccionado').style.display = 'block';
        document.getElementById('talleActual').textContent = talle;

        const cantidadInput = document.getElementById('cantidad');
        cantidadInput.max = stock;
        cantidadInput.value = 1;

        document.getElementById('btnAgregarCarrito').disabled = false;
    }

    function cambiarCantidad(cambio) {
        const cantidadInput = document.getElementById('cantidad');
        let nuevaCantidad = parseInt(cantidadInput.value) + cambio;

        if (nuevaCantidad < 1) nuevaCantidad = 1;
        if (nuevaCantidad > stockDisponible) nuevaCantidad = stockDisponible;

        cantidadInput.value = nuevaCantidad;
    }

    // Este código asegura que antes de enviar el formulario se asignan los valores correctos
    document.getElementById('formAgregarCarrito').addEventListener('submit', function(e) {
        if (!talleSeleccionado) {
            e.preventDefault();
            alert("Seleccioná un talle válido.");
            return;
        }

        const cantidad = document.getElementById('cantidad').value;
        document.getElementById('inputTalle').value = talleSeleccionado;
        document.getElementById('inputCantidad').value = cantidad;
    });
</script>