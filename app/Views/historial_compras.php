<main style="background: transparent;">
    <div class="historial-container">
        <div class="historial-header">
            <div class="header-content">
                <h1>🛍️ Mi Historial de Compras</h1>
                <p>Revisa todas tus compras anteriores</p>
            </div>
            <div class="header-actions">
                <a href="<?= base_url('carrito') ?>" class="btn btn-secondary">
                    ← Volver al Carrito
                </a>
                <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">
                    🛒 Seguir Comprando
                </a>
            </div>
        </div>

        <?php if (!empty($compras)): ?>
            <div class="compras-stats">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-info">
                        <h3>Total de Compras</h3>
                        <p class="stat-number"><?= count($compras) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>Total Gastado</h3>
                        <p class="stat-number">$<?= number_format(array_sum(array_column($compras, 'total')), 2) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <h3>Última Compra</h3>
                        <p class="stat-date"><?= date('d/m/Y', strtotime($compras[0]['fecha'])) ?></p>
                    </div>
                </div>
            </div>

            <div class="compras-lista">
                <?php foreach ($compras as $compra): ?>
                    <div class="compra-card">
                        <div class="compra-header">
                            <div class="compra-info">
                                <h3>🧾 Compra #<?= $compra['id_venta'] ?></h3>
                                <p class="fecha-compra">📅 <?= date('d/m/Y H:i', strtotime($compra['fecha'])) ?></p>
                            </div>
                            <div class="compra-total">
                                <span class="total-badge">$<?= number_format($compra['total'], 2) ?></span>
                            </div>
                        </div>

                        
                        <?php if (!empty($compra['datos_envio'])): ?>
                            <div class="compra-envio">
                                <h4>🚚 Datos de Envío</h4>
                                <div class="envio-info">
                                    <div class="envio-item">
                                        <span class="envio-label">📍 Localidad:</span>
                                        <span class="envio-value"><?= esc($compra['datos_envio']['localidad']) ?></span>
                                    </div>
                                    <div class="envio-item">
                                        <span class="envio-label">📞 Teléfono:</span>
                                        <span class="envio-value"><?= esc($compra['datos_envio']['telefono']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <div class="compra-productos">
                            <h4>🛍️ Productos Comprados</h4>
                            <div class="productos-grid">
                                <?php foreach ($compra['detalles'] as $detalle): ?>
                                    <div class="producto-card">
                                        <div class="producto-imagen">
                                            <?php if (!empty($detalle['imagen'])): ?>
                                                <img src="<?= base_url('assets/img/productos/' . $detalle['imagen']) ?>" 
                                                     alt="<?= esc($detalle['producto_nombre']) ?>">
                                            <?php else: ?>
                                                <div class="imagen-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="producto-info">
                                            <h5 class="producto-nombre">
                                                <?= esc($detalle['producto_nombre'] ?? 'Producto ID: ' . $detalle['producto_id']) ?>
                                            </h5>
                                            <div class="producto-detalles">
                                                <span class="talle">Talle: <strong><?= esc($detalle['talle']) ?></strong></span>
                                                <span class="cantidad">Cantidad: <strong><?= $detalle['cantidad'] ?></strong></span>
                                                <span class="precio">$<?= number_format($detalle['precio_unitario'], 2) ?> c/u</span>
                                            </div>
                                            <div class="producto-subtotal">
                                                <strong>Subtotal: $<?= number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="sin-compras">
                <div class="sin-compras-content">
                    <div class="sin-compras-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h3>Aún no has realizado compras</h3>
                    <p>¡Explora nuestro catálogo y encuentra productos increíbles!</p>
                    <div class="sin-compras-actions">
                        <a href="<?= base_url('catalogo') ?>" class="btn btn-primary">
                            🛒 Explorar Productos
                        </a>
                        <a href="<?= base_url('carrito') ?>" class="btn btn-secondary">
                            🛍️ Ver Carrito
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
    .historial-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: url('/assets/img/fondo.jpg') no-repeat center center fixed;
    background-size: cover;
    width: 90%;
    max-width: 1200px;
    margin: auto;
    background-color: rgba(40, 0, 0, 0.85);
    border-radius: 16px;
    padding: 30px;
    color: #fff;
    min-height: 80vh;
}

/* Header */
.historial-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #8B0000 0%, #B22222 100%);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 4px 15px rgba(139, 0, 0, 0.3);
}

.historial-header h1 {
    font-size: 2rem;
    margin: 0;
    font-weight: bold;
}

.historial-header p {
    margin: 5px 0 0;
    font-size: 1rem;
    opacity: 0.9;
}

.header-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

/* Estadísticas */
.compras-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid #444;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-info {
    flex: 1;
    min-width: 0; /* Permite que el contenido se comprima */
    overflow: hidden; /* Evita desbordamiento */
}

.stat-info h3 {
    margin: 0;
    font-size: 0.9rem;
    color: #ccc;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap; /* Mantiene el título en una línea */
    overflow: hidden;
    text-overflow: ellipsis; /* Agrega ... si es muy largo */
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 5px 0 0;
    color: #fff;
    word-break: break-word; /* Permite que el texto se rompa si es muy largo */
    line-height: 1.2; /* Reduce el espaciado entre líneas */
    overflow-wrap: break-word; /* Fuerza el salto de línea en palabras largas */
}

.stat-date {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 5px 0 0;
    color: #f1c40f;
}

/* Lista de compras */
.compras-lista {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.compra-card {
    background: linear-gradient(135deg, #1e1e1e 0%, #2c2c2c 100%);
    border: 1px solid #444;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s, box-shadow 0.3s;
}

.compra-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(241, 196, 15, 0.2);
}

.compra-header {
    background: linear-gradient(135deg, #8B0000 0%, #B22222 100%);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.compra-info h3 {
    margin: 0;
    font-size: 1.3rem;
    color: white;
}

.fecha-compra {
    margin: 5px 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.total-badge {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 1.3rem;
    font-weight: bold;
    color: #f1c40f;
}

/* Secciones de la compra */
.compra-envio, .compra-productos {
    padding: 20px;
    border-bottom: 1px solid #333;
}

.compra-productos {
    border-bottom: none;
}

.compra-envio h4, .compra-productos h4 {
    margin: 0 0 15px 0;
    color: #f1c40f;
    font-size: 1.1rem;
}

/* Datos de envío */
.envio-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.envio-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.envio-label {
    font-size: 0.9rem;
    color: #ccc;
}

.envio-value {
    font-weight: bold;
    color: #fff;
    font-size: 1rem;
}

/* Grid de productos */
.productos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
}

.producto-card {
    background-color: #333;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    gap: 15px;
    transition: background-color 0.3s;
}

.producto-card:hover {
    background-color: #3a3a3a;
}

.producto-imagen {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.producto-imagen img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.imagen-placeholder {
    width: 100%;
    height: 100%;
    background-color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 1.5rem;
}

.producto-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.producto-nombre {
    margin: 0;
    font-size: 1rem;
    color: #fff;
    font-weight: bold;
}

.producto-detalles {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.producto-detalles span {
    font-size: 0.85rem;
    color: #ccc;
}

.producto-subtotal {
    color: #f1c40f;
    font-size: 0.95rem;
    margin-top: auto;
}

/* Sin compras */
.sin-compras {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.sin-compras-content {
    text-align: center;
    color: #ccc;
    max-width: 400px;
}

.sin-compras-icon {
    font-size: 5rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.sin-compras-content h3 {
    color: #f1c40f;
    margin-bottom: 15px;
    font-size: 1.5rem;
}

.sin-compras-content p {
    margin-bottom: 25px;
    font-size: 1.1rem;
    line-height: 1.5;
}

.sin-compras-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Botones */
.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, #B22222 0%, #DC143C 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #DC143C 0%, #FF1493 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(178, 34, 34, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .historial-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .compras-stats {
        grid-template-columns: 1fr;
    }
    
    .compra-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .envio-info {
        grid-template-columns: 1fr;
    }
    
    .productos-grid {
        grid-template-columns: 1fr;
    }
    
    .producto-card {
        flex-direction: column;
        text-align: center;
    }
    
    .producto-imagen {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .sin-compras-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 200px;
        justify-content: center;
    }

    .stat-number {
        font-size: 1.4rem; /* Reduce el tamaño en móviles */
    }
}

@media (max-width: 480px) {
    .stat-number {
        font-size: 1.2rem;
    }
    
    .stat-card {
        padding: 15px; /* Reduce el padding para dar más espacio */
    }
}
</style>
