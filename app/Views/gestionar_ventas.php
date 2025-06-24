<main style="background: transparent;">
    <div class="admin-container">
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestión de Ventas</h1>
                <p>Revisa todas las ventas realizadas por los clientes</p>
            </div>
            <div class="header-actions">
                <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">
                    ← Volver a Operaciones
                </a>
            </div>
        </div>

        <?php if (!empty($ventas)): ?>
            <div class="ventas-grid">
                <?php foreach ($ventas as $venta): ?>
                    <div class="venta-card">
                        <div class="venta-info">
                            <h3>Venta #<?= $venta['id_venta'] ?></h3>
                            <p><strong>Usuario:</strong> <?= esc($venta['usuario']['nombre']) . ' ' . esc($venta['usuario']['apellido']) ?></p>
                            <p><strong>Fecha:</strong> <?= esc($venta['fecha']) ?></p>
                            <p><strong>Total:</strong> $<?= number_format($venta['total'], 2) ?></p>
                        </div>
                        <div class="venta-detalles">
                            <h4>Detalles:</h4>
                            <ul>
                                <?php foreach ($venta['detalles'] as $detalle): ?>
                                    <li>
                                        Producto ID: <?= $detalle['producto_id'] ?> | Talle: <?= $detalle['talle'] ?> | Cant: <?= $detalle['cantidad'] ?> | $<?= number_format($detalle['precio_unitario'], 2) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No se han registrado ventas todavía.</div>
        <?php endif; ?>
    </div>
</main>
