<main class="admin-background">
    <div class="admin-container">
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestionar Usuarios</h1>
                <p>Administra cuentas de usuarios, perfiles y estado de acceso</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModalCrear()">
                    <span class="btn-icon">+</span>
                    Crear Usuario
                </button>
                <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">← Volver a Operaciones</a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-header">
                <h3>Lista de Usuarios</h3>
                <div class="table-stats">
                    <span class="stat-item"><strong><?= count($usuarios) ?></strong> usuarios totales</span>
                    <span class="stat-item">
                        <strong><?= count(array_filter($usuarios, fn($u) => $u['perfil_id'] == 1)) ?></strong> administradores
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="<?= $usuario['baja'] === 'SI' ? 'user-inactive' : '' ?>">
                                <td style="color: black">#<?= esc($usuario['id_usuario']) ?></td>
                                <td style="color: black"><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></td>
                                <td style="color: black"><?= esc($usuario['email']) ?></td>
                                <td style="color: black">
                                    <span class="profile-badge <?= $usuario['perfil_id'] == 1 ? 'admin' : 'client' ?>">
                                        <?= $usuario['perfil_id'] == 1 ? 'Administrador' : 'Cliente' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?= $usuario['baja'] === 'NO' ? 'active' : 'inactive' ?>">
                                        <?= $usuario['baja'] === 'NO' ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['id_usuario'] != session()->get('usuario_id')): ?>
                                        <form method="post" action="<?= base_url('admin/toggle-estado-usuario/' . $usuario['id_usuario']) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-small <?= $usuario['baja'] === 'NO' ? 'btn-danger' : 'btn-success' ?>">
                                                <?= $usuario['baja'] === 'NO' ? 'Dar de baja' : 'Dar de alta' ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Tu sesión</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div id="modalCrear" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Crear Nuevo Usuario</h2>
                <button class="modal-close" onclick="cerrarModal('modalCrear')">&times;</button>
            </div>
            <form method="post" action="<?= base_url('admin/crear_usuario') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="crear_nombre">Nombre</label>
                            <input type="text" id="crear_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="crear_apellido">Apellido</label>
                            <input type="text" id="crear_apellido" name="apellido" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="crear_email">Email</label>
                        <input type="email" id="crear_email" name="email" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="crear_contraseña">Contraseña</label>
                            <input type="password" id="crear_contraseña" name="contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="crear_perfil">Perfil</label>
                            <select id="crear_perfil" name="perfil_id" required>
                                <option value="">Seleccionar perfil</option>
                                <option value="0">Cliente</option>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function abrirModalCrear() {
    document.getElementById('modalCrear').style.display = 'flex';
}
function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>
