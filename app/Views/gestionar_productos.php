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

        <!-- NUEVA BARRA DE BÚSQUEDA -->
        <div class="search-container">
            <div class="search-card">
                <div class="search-header">
                    <h3>🔍 Buscar Productos</h3>
                    <button type="button" class="btn-clear-search" onclick="limpiarBusqueda()" style="display: none;">
                        🗑️ Limpiar
                    </button>
                </div>
                <div class="search-form">
                    <div class="search-input-group">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Buscar por nombre... (ej: RONALDO, REAL MADRID, MESSI)" 
                               onkeyup="buscarProductos()"
                               onkeypress="handleEnterKey(event)">
                        <button type="button" class="search-btn" onclick="buscarProductos()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="search-results-info" id="searchResultsInfo" style="display: none;">
                        <span id="searchResultsText"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <?= session()->getFlashdata('success') ?>
                <?php if (session()->getFlashdata('nuevoTotal') !== null): ?>
                    <br>Nuevo stock total: <?= session()->getFlashdata('nuevoTotal') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <span class="alert-icon">✗</span>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        
        <div class="productos-grid" id="productosGrid">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="producto-card" data-nombre="<?= strtolower(esc($producto['nombre'])) ?>" data-categoria="<?= strtolower(esc($producto['categoria_nombre'] ?? '')) ?>">
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
                            <h3 class="producto-nombre" style= "color:black"><?= esc($producto['nombre']) ?></h3>
                            <p class="producto-categoria">
                                <?= esc($producto['categoria_nombre'] ?? 'Sin categoría') ?>
                                <?php if ($producto['id_categoria'] == 4): ?>
                                    <span style="color: orange;">(KIDS)</span>
                                <?php endif; ?>
                            </p>
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
                            <button onclick="toggleProducto(<?= $producto['id'] ?>, <?= $producto['activo'] ?>)" 
                                    class="btn-toggle <?= $producto['activo'] ? 'activo' : 'inactivo' ?>"
                                    title="<?= $producto['activo'] ? 'Desactivar' : 'Activar' ?> producto">
                                <?= $producto['activo'] ? '✅ Activo' : '❌ Inactivo' ?>
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

        <!-- Mensaje cuando no hay resultados de búsqueda -->
        <div class="no-resultados" id="noResultados" style="display: none;">
            <div class="no-resultados-content">
                <span class="no-resultados-icon">🔍</span>
                <h3>No se encontraron productos</h3>
                <p id="noResultadosTexto">No hay productos que coincidan con tu búsqueda.</p>
                <button class="btn btn-secondary" onclick="limpiarBusqueda()">
                    Mostrar todos los productos
                </button>
            </div>
        </div>
    </div>

    <!-- Modales existentes (sin cambios) -->
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
                                        <option value="<?= $categoria['id_categoria'] ?>">
                                            <?= esc($categoria['ct_nombre']) ?>
                                            <?php if ($categoria['id_categoria'] == 4): ?>
                                                (KIDS)
                                            <?php endif; ?>
                                        </option>
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
                                        <option value="<?= $categoria['id_categoria'] ?>">
                                            <?= esc($categoria['ct_nombre']) ?>
                                            <?php if ($categoria['id_categoria'] == 4): ?>
                                                (KIDS)
                                            <?php endif; ?>
                                        </option>
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
                <input type="hidden" name="producto_id" id="stockProductoId" value="">
                <div class="modal-body">
                    <div class="stock-grid" id="stockGrid">
                        <!-- Se llena dinámicamente -->
                    </div>
                    <div class="stock-actions" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                        <button type="button" onclick="resetearTodosLosStocks()" class="btn btn-warning btn-sm">
                            🔄 Resetear Todo a 0
                        </button>
                        <button type="button" onclick="aplicarStockATodos()" class="btn btn-info btn-sm">
                            📋 Aplicar Mismo Stock a Todos
                        </button>
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
// Variables globales para la búsqueda
let todosLosProductos = [];
let busquedaActiva = false;

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Guardar referencia a todos los productos
    todosLosProductos = Array.from(document.querySelectorAll('.producto-card'));
});


function buscarProductos() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const productosGrid = document.getElementById('productosGrid');
    const noResultados = document.getElementById('noResultados');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    const searchResultsText = document.getElementById('searchResultsText');
    const btnClearSearch = document.querySelector('.btn-clear-search');
    
    // Si no hay término de búsqueda, mostrar todos los productos
    if (searchTerm === '') {
        limpiarBusqueda();
        return;
    }
    
    busquedaActiva = true;
    btnClearSearch.style.display = 'inline-block';
    
    let productosEncontrados = 0;
    
    // Filtrar productos
    todosLosProductos.forEach(producto => {
        const nombre = producto.getAttribute('data-nombre');
        const categoria = producto.getAttribute('data-categoria');
        
        // Buscar en nombre y categoría
        const coincide = nombre.includes(searchTerm) || categoria.includes(searchTerm);
        
        if (coincide) {
            producto.style.display = 'block';
            productosEncontrados++;
            
            // Resaltar el término de búsqueda
            resaltarTermino(producto, searchTerm);
        } else {
            producto.style.display = 'none';
        }
    });
    
    // Mostrar información de resultados
    if (productosEncontrados > 0) {
        searchResultsInfo.style.display = 'block';
        searchResultsText.textContent = `Se encontraron ${productosEncontrados} producto(s) para "${searchTerm}"`;
        noResultados.style.display = 'none';
        productosGrid.style.display = 'grid';
    } else {
        searchResultsInfo.style.display = 'none';
        noResultados.style.display = 'block';
        productosGrid.style.display = 'none';
        document.getElementById('noResultadosTexto').textContent = `No se encontraron productos que coincidan con "${searchTerm}".`;
    }
}

// Función para resaltar el término de búsqueda
function resaltarTermino(producto, termino) {
    const nombreElement = producto.querySelector('.producto-nombre');
    const nombreOriginal = nombreElement.textContent;
    
    // Remover resaltado previo
    nombreElement.innerHTML = nombreOriginal;
    
    // Aplicar nuevo resaltado
    const regex = new RegExp(`(${termino})`, 'gi');
    const nombreResaltado = nombreOriginal.replace(regex, '<mark style="background-color: #f1c40f; padding: 2px 4px; border-radius: 3px;">$1</mark>');
    nombreElement.innerHTML = nombreResaltado;
}

// Función para limpiar la búsqueda
function limpiarBusqueda() {
    const searchInput = document.getElementById('searchInput');
    const productosGrid = document.getElementById('productosGrid');
    const noResultados = document.getElementById('noResultados');
    const searchResultsInfo = document.getElementById('searchResultsInfo');
    const btnClearSearch = document.querySelector('.btn-clear-search');
    
    // Limpiar input
    searchInput.value = '';
    
    // Mostrar todos los productos
    todosLosProductos.forEach(producto => {
        producto.style.display = 'block';
        
        // Remover resaltado
        const nombreElement = producto.querySelector('.producto-nombre');
        nombreElement.innerHTML = nombreElement.textContent;
    });
    
    // Ocultar elementos de búsqueda
    searchResultsInfo.style.display = 'none';
    noResultados.style.display = 'none';
    productosGrid.style.display = 'grid';
    btnClearSearch.style.display = 'none';
    
    busquedaActiva = false;
}

// Manejar Enter en el input de búsqueda
function handleEnterKey(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        buscarProductos();
    }
}

// Funciones existentes (sin cambios)
function abrirModalCrear() {
    document.getElementById('modalCrear').style.display = 'flex';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function gestionarStock(id) {
    document.getElementById('stockProductoId').value = id;
    
    fetch(`<?= base_url('admin/obtener_producto/') ?>${id}`)
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta');
        return response.json();
    })
    .then(data => {
        if (!data || data.error) {
            throw new Error(data?.error || 'Datos inválidos');
        }

        document.getElementById('stockProductoNombre').textContent = data.nombre;
        const stockGrid = document.getElementById('stockGrid');
        stockGrid.innerHTML = '';

        const esKids = (
            data.id_categoria == 4 || 
            data.debug_es_kids === true ||
            (data.categoria_nombre && data.categoria_nombre.toLowerCase() === 'kids')
        );

        const tallesDisponibles = esKids ? ['8', '10', '12', '14'] : ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        tallesDisponibles.forEach((talle) => {
            const stockItem = document.createElement('div');
            stockItem.className = 'stock-item';
            
            const stockExistente = data.stock_detalle?.find(s => String(s.talle) === String(talle));
            const valorActual = stockExistente?.stock ?? 0;
            
            stockItem.innerHTML = `
                <label for="stock_${talle}">Talle ${talle}</label>
                <input type="number" 
                       id="stock_${talle}" 
                       name="stock[${talle}]" 
                       value="${valorActual}" 
                       min="0" 
                       class="stock-input">
                <small class="stock-help">Stock actual: ${valorActual}</small>
            `;
            stockGrid.appendChild(stockItem);
        });

        document.getElementById('modalStock').style.display = 'flex';
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function resetearTodosLosStocks() {
    const inputs = document.querySelectorAll('#stockGrid input[type="number"]');
    inputs.forEach(input => {
        input.value = 0;
    });
}

function aplicarStockATodos() {
    const valor = prompt('¿Qué cantidad quieres aplicar a todos los talles?', '0');
    if (valor !== null && !isNaN(valor) && valor >= 0) {
        const inputs = document.querySelectorAll('#stockGrid input[type="number"]');
        inputs.forEach(input => {
            input.value = parseInt(valor);
        });
    }
}

function toggleProducto(id, estadoActual) {
    const accion = estadoActual ? 'desactivar' : 'activar';
    if (confirm(`¿Estás seguro de ${accion} este producto?`)) {
        window.location.href = `<?= base_url('admin/toggle_producto_activo/') ?>${id}`;
    }
}

function editarProducto(id) {
    fetch(`<?= base_url('admin/obtener_producto/') ?>${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            return;
        }
        
        document.getElementById('editar_id').value = data.id;
        document.getElementById('editar_nombre').value = data.nombre;
        document.getElementById('editar_precio').value = data.precio;
        document.getElementById('editar_descripcion').value = data.descripcion || '';
        document.getElementById('editar_categoria').value = data.id_categoria;
        
        if (data.imagen) {
            document.getElementById('imagenActual').style.display = 'block';
            document.getElementById('imagenActualPreview').src = `<?= base_url('assets/img/productos/') ?>${data.imagen}`;
        } else {
            document.getElementById('imagenActual').style.display = 'none';
        }
        
        document.getElementById('modalEditar').style.display = 'flex';
    })
    .catch(error => {
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

<style>
/* Estilos existentes para stock */
.stock-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #fafafa;
}

.stock-item label {
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.stock-input {
    padding: 8px;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.stock-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.3);
}

.stock-help {
    color: #666;
    font-size: 12px;
    margin-top: 3px;
}

.stock-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

/* NUEVOS ESTILOS PARA LA BÚSQUEDA */
.search-container {
    margin-bottom: 25px;
}

.search-card {
    background: linear-gradient(135deg, #2c1810 0%, #8B0000 100%);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #444;
    box-shadow: 0 4px 15px rgba(139, 0, 0, 0.3);
}

.search-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.search-header h3 {
    margin: 0;
    color: #f1c40f;
    font-size: 1.2rem;
}

.btn-clear-search {
    background: #dc3545;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-clear-search:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.search-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.search-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-input-group input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid #555;
    border-radius: 8px;
    background-color: #1e1e1e;
    color: #fff;
    font-size: 16px;
    transition: all 0.3s;
}

.search-input-group input:focus {
    outline: none;
    border-color: #f1c40f;
    box-shadow: 0 0 10px rgba(241, 196, 15, 0.3);
}

.search-input-group input::placeholder {
    color: #999;
}

.search-btn {
    background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%);
    color: #333;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.3s;
    min-width: 60px;
}

.search-btn:hover {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(241, 196, 15, 0.4);
}

.search-results-info {
    background: rgba(241, 196, 15, 0.1);
    border: 1px solid #f1c40f;
    border-radius: 6px;
    padding: 10px 15px;
    color: #f1c40f;
    font-weight: bold;
    font-size: 14px;
}

/* Estilos para cuando no hay resultados */
.no-resultados {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border: 2px dashed #555;
}

.no-resultados-content {
    text-align: center;
    color: #ccc;
    max-width: 400px;
}

.no-resultados-icon {
    font-size: 4rem;
    display: block;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-resultados-content h3 {
    color: #f1c40f;
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.no-resultados-content p {
    margin-bottom: 20px;
    font-size: 1rem;
    line-height: 1.5;
}

/* Estilos para resaltado de búsqueda */
mark {
    background-color: #f1c40f !important;
    color: #333 !important;
    padding: 2px 4px !important;
    border-radius: 3px !important;
    font-weight: bold !important;
}

/* Responsive para búsqueda */
@media (max-width: 768px) {
    .search-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .search-input-group {
        flex-direction: column;
    }
    
    .search-input-group input {
        width: 100%;
    }
    
    .search-btn {
        width: 100%;
    }
}

/* Animación para productos que aparecen/desaparecen */
.producto-card {
    transition: all 0.3s ease;
}

.producto-card[style*="display: none"] {
    opacity: 0;
    transform: scale(0.95);
}

.producto-card[style*="display: block"] {
    opacity: 1;
    transform: scale(1);
}
</style>
