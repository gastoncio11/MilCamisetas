<main class="admin-background">
    <div class="admin-container">
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestionar Consultas</h1>
                <p>Visualiza las consultas recibidas desde el formulario de contacto</p>
            </div>
            <div class="header-actions">
                <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">← Volver a Operaciones</a>
            </div>
        </div>

        <?php if (!empty($consultas)): ?>
            <div class="ventas-grid">
                <?php foreach ($consultas as $consulta): ?>
                    <div class="venta-card">
                        <div class="venta-info">
                            <h3><?= esc($consulta['nombre']) ?> <?= esc($consulta['apellido']) ?></h3>
                            <p><strong>Email:</strong> <?= esc($consulta['email']) ?></p>
                            <p><strong>Tel:</strong> <?= esc($consulta['telefono']) ?></p>
                            <p><strong>Asunto:</strong> <?= esc($consulta['asunto']) ?></p>
                            <p><strong>Mensaje:</strong> <?= esc($consulta['mensaje']) ?></p>
                            <p><strong>Fecha:</strong> <?= esc($consulta['fecha']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No hay consultas registradas.</div>
        <?php endif; ?>
    </div>
</main>
