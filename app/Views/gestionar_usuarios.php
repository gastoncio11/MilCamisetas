<main class="admin-background">
    <div class="admin-container">
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestionar Usuarios</h1>
                <p>Administra cuentas de usuarios, perfiles y permisos del sistema</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModalCrear()">
                    <span class="btn-icon">+</span>
                    Crear Usuario
                </button>
                <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">
                    ← Volver a Operaciones
                </a>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <span class="alert-icon">✗</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de usuarios -->
        <div class="table-container">
            <div class="table-header">
                <h3>Lista de Usuarios</h3>
                <div class="table-stats">
                    <span class="stat-item">
                        <strong><?= count($usuarios) ?></strong> usuarios totales
                    </span>
                    <span class="stat-item">
                        <strong><?= count(array_filter($usuarios, function($u) { return $u['perfil_id'] == 1; })) ?></strong> administradores
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="<?= $usuario['baja'] == 'SI' ? 'user-inactive' : '' ?>">
                                <td class="user-id">#<?= $usuario['id_usuario'] ?></td>
                                <td class="user-name">
                                    <div class="user-avatar">
                                        <?= strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)) ?>
                                    </div>
                                    <div class="user-info">
                                        <strong><?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?></strong>
                                    </div>
                                </td>
                                <td class="user-email"><?= esc($usuario['email']) ?></td>
                                <td class="user-profile">
                                    <span class="profile-badge <?= $usuario['perfil_id'] == 1 ? 'admin' : 'client' ?>">
                                        <?= $usuario['perfil_id'] == 1 ? 'Administrador' : 'Cliente' ?>
                                    </span>
                                </td>
                                <td class="user-status">
                                    <span class="status-badge <?= $usuario['baja'] == 'NO' ? 'active' : 'inactive' ?>">
                                        <?= $usuario['baja'] == 'NO' ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="user-actions">
                                    <?php if ($usuario['baja'] == 'NO'): ?>
                                        <button class="btn-action edit" onclick="editarUsuario(<?= $usuario['id_usuario'] ?>)" title="Editar">
                                            ✏️
                                        </button>
                                        <?php if ($usuario['id_usuario'] != session()->get('usuario_id')): ?>
                                            <button class="btn-action delete" onclick="confirmarEliminar(<?= $usuario['id_usuario'] ?>, '<?= esc($usuario['nombre'] . ' ' . $usuario['apellido']) ?>')" title="Eliminar">
                                                🗑️
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Usuario eliminado</span>
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

    <!-- Modal Editar Usuario -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Usuario</h2>
                <button class="modal-close" onclick="cerrarModal('modalEditar')">&times;</button>
            </div>
            <form method="post" id="formEditar">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editar_nombre">Nombre</label>
                            <input type="text" id="editar_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_apellido">Apellido</label>
                            <input type="text" id="editar_apellido" name="apellido" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editar_email">Email</label>
                        <input type="email" id="editar_email" name="email" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editar_contraseña">Nueva Contraseña (opcional)</label>
                            <input type="password" id="editar_contraseña" name="contraseña" placeholder="Dejar vacío para mantener actual">
                        </div>
                        <div class="form-group">
                            <label for="editar_perfil">Perfil</label>
                            <select id="editar_perfil" name="perfil_id" required>
                                <option value="0">Cliente</option>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmar Eliminación -->
    <div id="modalEliminar" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Confirmar Eliminación</h2>
                <button class="modal-close" onclick="cerrarModal('modalEliminar')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <span class="warning-icon">⚠️</span>
                    <p>¿Estás seguro de que deseas eliminar al usuario <strong id="nombreEliminar"></strong>?</p>
                    <p class="warning-text">Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEliminar')">Cancelar</button>
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Eliminar Usuario</a>
            </div>
        </div>
    </div>
</main>

<script>
// Funciones JavaScript para los modales
function abrirModalCrear() {
    document.getElementById('modalCrear').style.display = 'flex';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function editarUsuario(id) {
    const url = `<?= base_url('admin/obtener_usuario/') ?>${id}`;
    
    fetch(url)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        
        document.getElementById('editar_nombre').value = data.nombre;
        document.getElementById('editar_apellido').value = data.apellido;
        document.getElementById('editar_email').value = data.email;
        document.getElementById('editar_perfil').value = data.perfil_id;
        document.getElementById('editar_contraseña').value = '';
        
        document.getElementById('formEditar').action = `<?= base_url('admin/editar_usuario/') ?>${id}`;
        document.getElementById('modalEditar').style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos del usuario');
    });
}

function confirmarEliminar(id, nombre) {
    document.getElementById('nombreEliminar').textContent = nombre;
    document.getElementById('btnConfirmarEliminar').href = `<?= base_url('admin/eliminar_usuario/') ?>${id}`;
    document.getElementById('modalEliminar').style.display = 'flex';
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