<main >
    <div class="operaciones-container">
        <div class="operaciones-header">
            <h1>Panel de Operaciones</h1>
            <p>Administra tu sistema desde aquí</p>
        </div>

        <div class="operaciones-grid">
            <!-- Gestionar Usuarios -->
            <div class="operacion-card">
                <div class="card-icon">
                    <div class="icon-circle usuarios">
                        <span>👥</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>Gestionar Usuarios</h3>
                    <p>Administra cuentas de usuarios, perfiles y permisos del sistema</p>
                    <a href="<?= base_url('admin/gestionar_usuarios') ?>" class="btn-operacion">
                        Acceder
                        <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>

            <!-- Gestionar Productos -->
            <div class="operacion-card">
                <div class="card-icon">
                    <div class="icon-circle productos">
                        <span>📦</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>Gestionar Productos</h3>
                    <p>Añade, edita y controla el inventario de productos disponibles</p>
                    <a href="<?= base_url('admin/gestionar_productos') ?>" class="btn-operacion">
                        Acceder
                        <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>

            <!-- Gestionar Consultas -->
            <div class="operacion-card">
                <div class="card-icon">
                    <div class="icon-circle consultas">
                        <span>💬</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>Gestionar Consultas</h3>
                    <p>Revisa y responde las consultas y mensajes de los clientes</p>
                    <a href="<?= base_url('admin/gestionar_consultas') ?>" class="btn-operacion">
                        Acceder
                        <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>

            <!-- Gestionar Ventas -->
            <div class="operacion-card">
                <div class="card-icon">
                    <div class="icon-circle ventas">
                        <span>💰</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>Gestionar Ventas</h3>
                    <p>Supervisa pedidos, facturas y estadísticas de ventas</p>
                    <a href="<?= base_url('admin/gestionar_ventas') ?>" class="btn-operacion">
                        Acceder
                        <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>