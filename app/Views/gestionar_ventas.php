<main style="background: transparent;">
    <div class="admin-container">
        
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestionar Ventas</h1>
                <p>Administra y visualiza todas las ventas realizadas</p>
            </div>
            <div class="header-actions">
                <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">
                    ← Volver a Operaciones
                </a>
            </div>
        </div>

        
        <div class="filtros-container">
            <form method="get" class="filtros-form">
                <div class="filtro-grupo">
                    <label for="anio">Año:</label>
                    <select name="anio" id="anio">
                        <option value="">Todos los años</option>
                        <?php foreach ($anios_disponibles as $anio_item): ?>
                            <option value="<?= $anio_item['anio'] ?>" <?= ($anio_actual == $anio_item['anio']) ? 'selected' : '' ?>>
                                <?= $anio_item['anio'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filtro-grupo">
                    <label for="mes">Mes:</label>
                    <select name="mes" id="mes">
                        <option value="">Todos los meses</option>
                        <option value="1" <?= ($mes_actual == 1) ? 'selected' : '' ?>>Enero</option>
                        <option value="2" <?= ($mes_actual == 2) ? 'selected' : '' ?>>Febrero</option>
                        <option value="3" <?= ($mes_actual == 3) ? 'selected' : '' ?>>Marzo</option>
                        <option value="4" <?= ($mes_actual == 4) ? 'selected' : '' ?>>Abril</option>
                        <option value="5" <?= ($mes_actual == 5) ? 'selected' : '' ?>>Mayo</option>
                        <option value="6" <?= ($mes_actual == 6) ? 'selected' : '' ?>>Junio</option>
                        <option value="7" <?= ($mes_actual == 7) ? 'selected' : '' ?>>Julio</option>
                        <option value="8" <?= ($mes_actual == 8) ? 'selected' : '' ?>>Agosto</option>
                        <option value="9" <?= ($mes_actual == 9) ? 'selected' : '' ?>>Septiembre</option>
                        <option value="10" <?= ($mes_actual == 10) ? 'selected' : '' ?>>Octubre</option>
                        <option value="11" <?= ($mes_actual == 11) ? 'selected' : '' ?>>Noviembre</option>
                        <option value="12" <?= ($mes_actual == 12) ? 'selected' : '' ?>>Diciembre</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="<?= base_url('admin/gestionar_ventas') ?>" class="btn btn-secondary">Limpiar</a>
            </form>
        </div>

        
        <?php if (!empty($estadisticas)): ?>
            <div class="estadisticas-container">
                <div class="estadistica-card">
                    <div class="estadistica-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="estadistica-content">
                        <h3><?= $estadisticas['total_ventas'] ?></h3>
                        <p>Ventas en <?= $estadisticas['mes_nombre'] ?> <?= $estadisticas['anio'] ?></p>
                    </div>
                </div>
                
                <div class="estadistica-card">
                    <div class="estadistica-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="estadistica-content">
                        <h3>$<?= number_format($estadisticas['total_ingresos'], 2) ?></h3>
                        <p>Ingresos Totales</p>
                    </div>
                </div>
                
                <div class="estadistica-card">
                    <div class="estadistica-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="estadistica-content">
                        <h3>$<?= number_format($estadisticas['promedio_venta'], 2) ?></h3>
                        <p>Promedio por Venta</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="ventas-container">
            <?php if (!empty($ventas)): ?>
                <?php foreach ($ventas as $venta): ?>
                    <div class="venta-card">
                        <div class="venta-header">
                            <div class="venta-info">
                                <h3>Venta #<?= $venta['id_venta'] ?></h3>
                                <p class="venta-fecha"><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></p>
                                <p class="venta-cliente">
                                    <i class="fas fa-user"></i>
                                    <?= esc($venta['nombre'] ?? 'Cliente') ?> <?= esc($venta['apellido'] ?? '') ?>
                                    <?php if (!empty($venta['email'])): ?>
                                        <br><small><?= esc($venta['email']) ?></small>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="venta-total">
                                <span class="total-label">Total:</span>
                                <span class="total-amount">$<?= number_format($venta['total'], 2) ?></span>
                                
                                <div class="metodo-pago-badge">
                                    <?php
                                    $metodo = $venta['metodo_pago'] ?? 'tarjeta';
                                    $iconos = [
                                        'efectivo' => 'fas fa-money-bill-wave',
                                        'tarjeta' => 'fas fa-credit-card',
                                        'mercadopago' => 'fab fa-cc-mastercard'
                                    ];
                                    $colores = [
                                        'efectivo' => 'badge-efectivo',
                                        'tarjeta' => 'badge-tarjeta',
                                        'mercadopago' => 'badge-mercadopago'
                                    ];
                                    ?>
                                    <span class="metodo-badge <?= $colores[$metodo] ?? 'badge-tarjeta' ?>">
                                        <i class="<?= $iconos[$metodo] ?? 'fas fa-credit-card' ?>"></i>
                                        <?= ucfirst($metodo) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="venta-detalles">
                            <div class="productos-vendidos">
                                <h4>Productos:</h4>
                                <?php if (!empty($venta['detalles'])): ?>
                                    <div class="productos-lista">
                                        <?php foreach ($venta['detalles'] as $detalle): ?>
                                            <div class="producto-item">
                                                <span class="producto-nombre">
                                                    <?= esc($detalle['producto_nombre'] ?? 'Producto') ?>
                                                    <?php if (!empty($detalle['talle'])): ?>
                                                        - Talle <?= esc($detalle['talle']) ?>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="producto-cantidad">x<?= $detalle['cantidad'] ?></span>
                                                <span class="producto-precio">$<?= number_format($detalle['precio_unitario'], 2) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <?php if (!empty($venta['datos_envio'])): ?>
                                <div class="datos-envio">
                                    <h4>Datos de Envío:</h4>
                                    <div class="envio-info">
                                        <div class="envio-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= esc($venta['datos_envio']['localidad']) ?></span>
                                        </div>
                                        <div class="envio-item">
                                            <i class="fas fa-phone"></i>
                                            <span><?= esc($venta['datos_envio']['telefono']) ?></span>
                                        </div>
                                        
                                        
                                        <?php if ($metodo === 'tarjeta' && !empty($venta['datos_envio']['numero_tarjeta'])): ?>
                                            <div class="envio-item">
                                                <i class="fas fa-credit-card"></i>
                                                <span>Tarjeta: ****<?= substr($venta['datos_envio']['numero_tarjeta'], -4) ?></span>
                                            </div>
                                        <?php elseif ($metodo === 'efectivo'): ?>
                                            <div class="envio-item">
                                                <i class="fas fa-money-bill-wave"></i>
                                                <span>Pago en efectivo al recibir</span>
                                            </div>
                                        <?php elseif ($metodo === 'mercadopago'): ?>
                                            <div class="envio-item">
                                                <i class="fab fa-cc-mastercard"></i>
                                                <span>Pagado con MercadoPago</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-ventas">
                    <div class="no-ventas-content">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>No hay ventas registradas</h3>
                        <p>No se encontraron ventas para los filtros seleccionados</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>