<main style="background: transparent;">
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <div class="header-content">
                <h1>Gestionar Productos</h1>
                <p>Administra el inventario y catálogo de productos</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="abrirModalCrear()">
                    <span class="btn-icon">+</span>
                    Agregar Producto
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

        <!-- Grid de productos -->
        <div class="productos-grid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="producto-card">
                        <div class="producto-imagen">
                            <?php if (!empty($producto['imagen'])): ?>
                                <img src="<?= base_url('assets/img/productos/' . $producto['imagen']) ?>" alt="<?= esc($producto['nombre']) ?>">
                            <?php else: ?>
                                <div class="imagen-placeholder">
                                    <span>📷</span>
                                    <p>Sin imagen</p>
                                </div>
                            <?php endif; ?>
                            <div class="producto-estado <?= $producto['activo'] ? 'activo' : 'inactivo' ?>">
                                <?= $producto['activo'] ? 'Activo' : 'Inactivo' ?>
                            </div>
                        </div>
                        
                        <div class="producto-info">
                            <h3 class="producto-nombre"><?= esc($producto['nombre']) ?></h3>
                            <p class="producto-categoria"><?= esc($producto['categoria_nombre'] ?? 'Sin categoría') ?></p>
                            <p class="producto-precio">$<?= number_format($producto['precio'], 2) ?></p>
                            
                            <?php if (!empty($producto['descripcion'])): ?>
                                <p class="producto-descripcion"><?= esc(substr($producto['descripcion'], 0, 100)) ?>...</p>
                            <?php endif; ?>
                            
                            <div class="stock-resumen">
                                <div class="stock-total">
                                    <strong>Stock Total: <?= $producto['stock_total'] ?? 0 ?></strong>
                                </div>
                                <div class="stock-talles">
                                    <?php if (isset($producto['stock_detalle']) && !empty($producto['stock_detalle'])): ?>
                                        <?php foreach ($producto['stock_detalle'] as $stock): ?>
                                            <span class="talle-stock <?= $stock['stock'] > 0 ? 'disponible' : 'agotado' ?>">
                                                <?= $stock['talle'] ?>: <?= $stock['stock'] ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="sin-stock">Sin stock configurado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="producto-acciones">
                            <button class="btn-accion stock" onclick="gestionarStock(<?= $producto['id'] ?>)" title="Gestionar Stock">
                                📦
                            </button>
                            <button class="btn-accion edit" onclick="editarProducto(<?= $producto['id'] ?>)" title="Editar">
                                ✏️
                            </button>
                            <button class="btn-accion delete" onclick="confirmarEliminar(<?= $producto['id'] ?>, '<?= esc($producto['nombre']) ?>')" title="Eliminar">
                                🗑️
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-productos">
                    <div class="no-productos-content">
                        <span class="no-productos-icon">📦</span>
                        <h3>No hay productos registrados</h3>
                        <p>Comienza agregando tu primer producto</p>
                        <button class="btn btn-primary" onclick="abrirModalCrear()">
                            Agregar Producto
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Crear Producto -->
    <div id="modalCrear" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2>Agregar Nuevo Producto</h2>
                <button class="modal-close" onclick="cerrarModal('modalCrear')">&times;</button>
            </div>
            <form method="post" action="<?= base_url('admin/crear_producto') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="crear_nombre">Nombre del Producto</label>
                            <input type="text" id="crear_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="crear_precio">Precio</label>
                            <input type="number" id="crear_precio" name="precio" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="crear_descripcion">Descripción</label>
                        <textarea id="crear_descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="crear_categoria">Categoría</label>
                            <select id="crear_categoria" name="id_categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id_categoria'] ?>"><?= esc($categoria['ct_nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="crear_imagen">Imagen del Producto</label>
                            <input type="file" id="crear_imagen" name="imagen" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Producto -->
    <div id="modalEditar" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2>Editar Producto</h2>
                <button class="modal-close" onclick="cerrarModal('modalEditar')">&times;</button>
            </div>
            <form method="post" action="<?= base_url('admin/editar_producto') ?>" enctype="multipart/form-data" id="formEditar">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editar_nombre">Nombre del Producto</label>
                            <input type="text" id="editar_nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editar_precio">Precio</label>
                            <input type="number" id="editar_precio" name="precio" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editar_descripcion">Descripción</label>
                        <textarea id="editar_descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editar_categoria">Categoría</label>
                            <select id="editar_categoria" name="id_categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?= $categoria['id_categoria'] ?>"><?= esc($categoria['ct_nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editar_imagen">Nueva Imagen (opcional)</label>
                            <input type="file" id="editar_imagen" name="imagen" accept="image/*">
                            <small class="form-text">Deja vacío para mantener la imagen actual</small>
                        </div>
                    </div>
                    
                    <div class="imagen-actual" id="imagenActual" style="display: none;">
                        <label>Imagen actual:</label>
                        <img id="imagenActualPreview" src="/placeholder.svg" alt="Imagen actual" style="max-width: 200px; height: auto; border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Gestionar Stock -->
    <div id="modalStock" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Gestionar Stock - <span id="stockProductoNombre"></span></h2>
                <button class="modal-close" onclick="cerrarModal('modalStock')">&times;</button>
            </div>
            <form method="post" action="<?= base_url('admin/actualizar_stock') ?>" id="formStock">
                <?= csrf_field() ?>
                <input type="hidden" name="producto_id" id="stockProductoId">
                <div class="modal-body">
                    <div class="stock-grid" id="stockGrid">
                        <!-- Se llena dinámicamente -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalStock')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Stock</button>
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
                    <p>¿Estás seguro de que deseas eliminar el producto <strong id="nombreEliminar"></strong>?</p>
                    <p class="warning-text">Esta acción ocultará el producto del catálogo.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal('modalEliminar')">Cancelar</button>
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Eliminar Producto</a>
            </div>
        </div>
    </div>
</main>

<script>
function abrirModalCrear() {
    document.getElementById('modalCrear').style.display = 'flex';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function gestionarStock(id) {
    fetch(`<?= base_url('admin/obtener_producto/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        
        document.getElementById('stockProductoNombre').textContent = data.nombre;
        document.getElementById('stockProductoId').value = id;
        
        const stockGrid = document.getElementById('stockGrid');
        stockGrid.innerHTML = '';
        
        if (data.stock_detalle && data.stock_detalle.length > 0) {
            data.stock_detalle.forEach(stock => {
                const stockItem = document.createElement('div');
                stockItem.className = 'stock-item';
                stockItem.innerHTML = `
                    <label for="stock_${stock.talle}">Talle ${stock.talle}</label>
                    <input type="number" id="stock_${stock.talle}" name="stock[${stock.talle}]" 
                           value="${stock.stock}" min="0" class="stock-input">
                `;
                stockGrid.appendChild(stockItem);
            });
        } else {
            // Crear talles por defecto si no existen
            let talles = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

            if (data.categoria_nombre && data.categoria_nombre.toLowerCase() === 'kids') {
                talles = ['8', '10', '12', '14'];
            }

            talles.forEach(talle => {
                const stockItem = document.createElement('div');
                stockItem.className = 'stock-item';
                stockItem.innerHTML = `
                    <label for="stock_${talle}">Talle ${talle}</label>
                    <input type="number" id="stock_${talle}" name="stock[${talle}]" 
                           value="0" min="0" class="stock-input">
                `;
                stockGrid.appendChild(stockItem);
            });
        }
        
        document.getElementById('modalStock').style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos del producto');
    });
}

function editarProducto(id) {
    fetch(`<?= base_url('admin/obtener_producto/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        
        // Llenar el formulario de edición
        document.getElementById('editar_id').value = data.id;
        document.getElementById('editar_nombre').value = data.nombre;
        document.getElementById('editar_precio').value = data.precio;
        document.getElementById('editar_descripcion').value = data.descripcion || '';
        document.getElementById('editar_categoria').value = data.id_categoria;
        
        // Mostrar imagen actual si existe
        if (data.imagen) {
            document.getElementById('imagenActual').style.display = 'block';
            document.getElementById('imagenActualPreview').src = `<?= base_url('assets/img/productos/') ?>${data.imagen}`;
        } else {
            document.getElementById('imagenActual').style.display = 'none';
        }
        
        document.getElementById('modalEditar').style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar los datos del producto');
    });
}

function confirmarEliminar(id, nombre) {
    document.getElementById('nombreEliminar').textContent = nombre;
    document.getElementById('btnConfirmarEliminar').href = `<?= base_url('admin/eliminar_producto/') ?>${id}`;
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