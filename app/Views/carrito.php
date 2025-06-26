<?php 
$cart = \Config\Services::cart(); 

// INICIALIZAR TOTAL DESDE EL PRINCIPIO
$total = 0;
$items = $cart->contents();

// Calcular total si hay items
if (!empty($items)) {
    foreach ($items as $item) {
        $total += $item['subtotal'];
    }
}
?>

<div class="carrito-container">
    <div class="carrito-header">
        <h1>Carrito de Compras</h1>
        <div class="header-buttons">
            <a href="<?= base_url('catalogo') ?>" class="btn-continuar">
                <i class="fas fa-arrow-left"></i> Continuar Comprando
            </a>
            <?php if (session()->get('usuario_logueado')): ?>
                <a href="<?= base_url('usuario/historial_compras') ?>" class="btn-historial">
                    <i class="fas fa-history"></i> Historial de Compras
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alerta-error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alerta-exito">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="carrito-vacio">
            <div class="vacio-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Tu carrito está vacío</h2>
            <p>¡Agrega algunos productos para comenzar!</p>
            <a href="<?= base_url('catalogo') ?>" class="btn-explorar">Explorar Productos</a>
        </div>
    <?php else: ?>
        <div class="carrito-contenido">
            <div class="items-carrito">
                <?php
                $i = 1;
                foreach ($items as $item): ?>
                    <div class="item-carrito">
                        <div class="item-numero">
                            <span class="numero"><?= $i++ ?></span>
                        </div>
                        <div class="item-info">
                            <h3 class="item-nombre"><?= esc($item['name']) ?> - Talle <?= esc($item['options']['talle'] ?? '-') ?></h3>
                            <div class="item-detalles">
                                <span class="precio-unitario">$<?= number_format($item['price'], 2) ?></span>
                                <span class="separador">×</span>
                                <span class="cantidad"><?= $item['qty'] ?></span>
                            </div>
                        </div>
                        <div class="item-subtotal">
                            <span class="subtotal-valor">$<?= number_format($item['subtotal'], 2) ?></span>
                        </div>
                        <div class="item-acciones">
                            <a href="<?= base_url('carrito/eliminar/' . $item['rowid']) ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="carrito-resumen">
                <div class="resumen-card">
                    <h3>Resumen del Pedido</h3>
                    <div class="resumen-linea">
                        <span>Subtotal:</span>
                        <span class="precio">$<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="resumen-linea">
                        <span>Envío:</span>
                        <span class="precio">Gratis</span>
                    </div>
                    <hr>
                    <div class="resumen-total">
                        <span>Total:</span>
                        <span class="precio-total">$<?= number_format($total, 2) ?></span>
                    </div>
                    
                    <div class="acciones-finales">
                        <a href="<?= base_url('carrito/vaciar') ?>" class="btn-vaciar" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                            <i class="fas fa-trash-alt"></i> Vaciar Carrito
                        </a>
                        <button type="button" class="btn-ordenar" onclick="abrirSeleccionMetodoPago()">
                            <i class="fas fa-check"></i> Finalizar Compra
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL SELECCIÓN DE MÉTODO DE PAGO -->
        <div id="modalMetodoPago" class="modal">
            <div class="modal-content modal-metodo-pago">
                <div class="modal-header">
                    <h2>💳 Selecciona tu Método de Pago</h2>
                    <button class="modal-close" onclick="cerrarModal('modalMetodoPago')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="metodos-pago-grid">
                        <!-- EFECTIVO -->
                        <div class="metodo-pago-card" onclick="seleccionarMetodo('efectivo')">
                            <div class="metodo-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h3>Efectivo</h3>
                            <p>Paga en efectivo al recibir tu pedido</p>
                            <div class="metodo-beneficio">
                                <i class="fas fa-check"></i> Sin comisiones
                            </div>
                        </div>

                        <!-- TARJETA -->
                        <div class="metodo-pago-card" onclick="seleccionarMetodo('tarjeta')">
                            <div class="metodo-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>Tarjeta de Crédito</h3>
                            <p>Paga con tu tarjeta de crédito o débito</p>
                            <div class="metodo-beneficio">
                                <i class="fas fa-shield-alt"></i> Pago seguro
                            </div>
                        </div>

                        <!-- MERCADO PAGO -->
                        <div class="metodo-pago-card" onclick="seleccionarMetodo('mercadopago')">
                            <div class="metodo-icon">
                                <i class="fab fa-cc-mastercard"></i>
                            </div>
                            <h3>MercadoPago</h3>
                            <p>Pago rápido y seguro con MercadoPago</p>
                            <div class="metodo-beneficio">
                                <i class="fas fa-bolt"></i> Pago instantáneo
                            </div>
                        </div>
                    </div>

                    <div class="resumen-compra-metodo">
                        <div class="resumen-total-metodo">
                            <strong>Total a Pagar: $<?= number_format($total, 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL PAGO EFECTIVO -->
        <div id="modalEfectivo" class="modal">
            <div class="modal-content modal-checkout">
                <div class="modal-header">
                    <h2>💵 Pago en Efectivo</h2>
                    <button class="modal-close" onclick="cerrarModal('modalEfectivo')">&times;</button>
                </div>
                <form method="post" action="<?= base_url('guardar-venta') ?>" id="formEfectivo">
                    <?= csrf_field() ?>
                    <input type="hidden" name="metodo_pago" value="efectivo">
                    <div class="modal-body">
                        <div class="checkout-section">
                            <h3>📍 Datos de Envío</h3>
                            <div class="form-group">
                                <label for="localidad_efectivo">Localidad / Ciudad</label>
                                <input type="text" id="localidad_efectivo" name="localidad" required placeholder="Ej: Buenos Aires, Córdoba, Rosario...">
                                <small class="form-help">Ingresa la ciudad donde quieres recibir tu pedido</small>
                            </div>
                            <div class="form-group">
                                <label for="telefono_efectivo">Teléfono de Contacto</label>
                                <input type="tel" id="telefono_efectivo" name="telefono" required placeholder="Ej: +54 9 11 1234-5678">
                                <small class="form-help">Para coordinar la entrega</small>
                            </div>
                        </div>

                        <div class="checkout-info-efectivo">
                            <div class="info-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="info-content">
                                <h4>Instrucciones de Pago</h4>
                                <p>Pagarás en efectivo al momento de recibir tu pedido. Te enviaremos un correo con todos los detalles de tu compra.</p>
                            </div>
                        </div>

                        <div class="checkout-resumen">
                            <h3>📋 Resumen de tu Pedido</h3>
                            <div class="resumen-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="resumen-item">
                                        <span><?= esc($item['name']) ?> - Talle <?= esc($item['options']['talle']) ?> (x<?= $item['qty'] ?>)</span>
                                        <span>$<?= number_format($item['subtotal'], 2) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="resumen-total-modal">
                                <strong>Total a Pagar en Efectivo: $<?= number_format($total, 2) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="volverAMetodos()">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <button type="submit" class="btn btn-success btn-confirmar-efectivo">
                            <i class="fas fa-money-bill-wave"></i> Confirmar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL PAGO TARJETA -->
        <div id="modalTarjeta" class="modal">
            <div class="modal-content modal-checkout">
                <div class="modal-header">
                    <h2>💳 Pago con Tarjeta</h2>
                    <button class="modal-close" onclick="cerrarModal('modalTarjeta')">&times;</button>
                </div>
                <form method="post" action="<?= base_url('guardar-venta') ?>" id="formTarjeta">
                    <?= csrf_field() ?>
                    <input type="hidden" name="metodo_pago" value="tarjeta">
                    <div class="modal-body">
                        <div class="checkout-section">
                            <h3>📍 Datos de Envío</h3>
                            <div class="form-group">
                                <label for="localidad_tarjeta">Localidad / Ciudad</label>
                                <input type="text" id="localidad_tarjeta" name="localidad" required placeholder="Ej: Buenos Aires, Córdoba, Rosario...">
                                <small class="form-help">Ingresa la ciudad donde quieres recibir tu pedido</small>
                            </div>
                            <div class="form-group">
                                <label for="telefono_tarjeta">Teléfono de Contacto</label>
                                <input type="tel" id="telefono_tarjeta" name="telefono" required placeholder="Ej: +54 9 11 1234-5678">
                                <small class="form-help">Para coordinar la entrega</small>
                            </div>
                        </div>

                        <div class="checkout-section">
                            <h3>💳 Datos de la Tarjeta</h3>
                            <div class="form-group">
                                <label for="numero_tarjeta">Número de Tarjeta</label>
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta" required 
                                       placeholder="1234 5678 9012 3456" maxlength="19"
                                       oninput="formatearTarjeta(this)">
                                <small class="form-help">Ingresa los 16 dígitos de tu tarjeta</small>
                            </div>
                            <div class="form-group">
                                <label for="cvv">Código de Seguridad (CVV)</label>
                                <input type="text" id="cvv" name="cvv" required 
                                       placeholder="123" maxlength="3"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="form-help">Los 3 dígitos del reverso de tu tarjeta</small>
                            </div>
                        </div>

                        <div class="checkout-resumen">
                            <h3>📋 Resumen de tu Pedido</h3>
                            <div class="resumen-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="resumen-item">
                                        <span><?= esc($item['name']) ?> - Talle <?= esc($item['options']['talle']) ?> (x<?= $item['qty'] ?>)</span>
                                        <span>$<?= number_format($item['subtotal'], 2) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="resumen-total-modal">
                                <strong>Total a Pagar: $<?= number_format($total, 2) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="volverAMetodos()">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <button type="submit" class="btn btn-primary btn-confirmar-tarjeta">
                            <i class="fas fa-credit-card"></i> Pagar con Tarjeta
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL MERCADO PAGO -->
        <div id="modalMercadoPago" class="modal">
            <div class="modal-content modal-checkout">
                <div class="modal-header">
                    <h2>🚀 MercadoPago</h2>
                    <button class="modal-close" onclick="cerrarModal('modalMercadoPago')">&times;</button>
                </div>
                <form method="post" action="<?= base_url('guardar-venta') ?>" id="formMercadoPago">
                    <?= csrf_field() ?>
                    <input type="hidden" name="metodo_pago" value="mercadopago">
                    <div class="modal-body">
                        <div class="checkout-section">
                            <h3>📍 Datos de Envío</h3>
                            <div class="form-group">
                                <label for="localidad_mp">Localidad / Ciudad</label>
                                <input type="text" id="localidad_mp" name="localidad" required placeholder="Ej: Buenos Aires, Córdoba, Rosario...">
                                <small class="form-help">Ingresa la ciudad donde quieres recibir tu pedido</small>
                            </div>
                            <div class="form-group">
                                <label for="telefono_mp">Teléfono de Contacto</label>
                                <input type="tel" id="telefono_mp" name="telefono" required placeholder="Ej: +54 9 11 1234-5678">
                                <small class="form-help">Para coordinar la entrega</small>
                            </div>
                        </div>

                        <div class="mercadopago-info">
                            <div class="mp-logo">
                                <i class="fab fa-cc-mastercard"></i>
                                <span>MercadoPago</span>
                            </div>
                            <p>Serás redirigido a MercadoPago para completar tu pago de forma segura.</p>
                            <div class="mp-beneficios">
                                <div class="mp-beneficio">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Pago 100% seguro</span>
                                </div>
                                <div class="mp-beneficio">
                                    <i class="fas fa-bolt"></i>
                                    <span>Procesamiento instantáneo</span>
                                </div>
                                <div class="mp-beneficio">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Múltiples medios de pago</span>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-resumen">
                            <h3>📋 Resumen de tu Pedido</h3>
                            <div class="resumen-items">
                                <?php foreach ($items as $item): ?>
                                    <div class="resumen-item">
                                        <span><?= esc($item['name']) ?> - Talle <?= esc($item['options']['talle']) ?> (x<?= $item['qty'] ?>)</span>
                                        <span>$<?= number_format($item['subtotal'], 2) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="resumen-total-modal">
                                <strong>Total a Pagar: $<?= number_format($total, 2) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="volverAMetodos()">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                        <button type="submit" class="btn btn-mercadopago btn-confirmar-mp">
                            <i class="fab fa-cc-mastercard"></i> Pagar con MercadoPago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
<?php if (!empty($items)): ?>
const totalCarrito = <?= $total ?>;

function abrirSeleccionMetodoPago() {
    document.getElementById('modalMetodoPago').style.display = 'flex';
}

function seleccionarMetodo(metodo) {
    cerrarModal('modalMetodoPago');
    
    switch(metodo) {
        case 'efectivo':
            document.getElementById('modalEfectivo').style.display = 'flex';
            break;
        case 'tarjeta':
            document.getElementById('modalTarjeta').style.display = 'flex';
            break;
        case 'mercadopago':
            document.getElementById('modalMercadoPago').style.display = 'flex';
            break;
    }
}

function volverAMetodos() {
    // Cerrar todos los modales de pago
    cerrarModal('modalEfectivo');
    cerrarModal('modalTarjeta');
    cerrarModal('modalMercadoPago');
    
    // Abrir modal de selección
    document.getElementById('modalMetodoPago').style.display = 'flex';
}

function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function formatearTarjeta(input) {
    let valor = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    valor = valor.substring(0, 16);
    let valorFormateado = valor.match(/.{1,4}/g)?.join(' ') || valor;
    input.value = valorFormateado;
}

// Validación para efectivo
document.getElementById('formEfectivo').addEventListener('submit', function(e) {
    const localidad = document.getElementById('localidad_efectivo').value.trim();
    const telefono = document.getElementById('telefono_efectivo').value.trim();
    
    if (!localidad || !telefono) {
        e.preventDefault();
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }
    
    const totalFormateado = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS'
    }).format(totalCarrito);
    
    if (!confirm('¿Confirmas tu pedido por ' + totalFormateado + ' a pagar en efectivo?')) {
        e.preventDefault();
    }
});

// Validación para tarjeta
document.getElementById('formTarjeta').addEventListener('submit', function(e) {
    const localidad = document.getElementById('localidad_tarjeta').value.trim();
    const telefono = document.getElementById('telefono_tarjeta').value.trim();
    const numeroTarjeta = document.getElementById('numero_tarjeta').value.replace(/\s/g, '');
    const cvv = document.getElementById('cvv').value.trim();
    
    if (!localidad || !telefono || !numeroTarjeta || !cvv) {
        e.preventDefault();
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }
    
    if (numeroTarjeta.length !== 16) {
        e.preventDefault();
        alert('El número de tarjeta debe tener 16 dígitos.');
        return;
    }
    
    if (cvv.length !== 3) {
        e.preventDefault();
        alert('El CVV debe tener 3 dígitos.');
        return;
    }
    
    const totalFormateado = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS'
    }).format(totalCarrito);
    
    if (!confirm('¿Confirmas tu compra por ' + totalFormateado + '?')) {
        e.preventDefault();
    }
});

// Validación para MercadoPago
document.getElementById('formMercadoPago').addEventListener('submit', function(e) {
    const localidad = document.getElementById('localidad_mp').value.trim();
    const telefono = document.getElementById('telefono_mp').value.trim();
    
    if (!localidad || !telefono) {
        e.preventDefault();
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }
    
    const totalFormateado = new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS'
    }).format(totalCarrito);
    
    if (!confirm('Serás redirigido a MercadoPago para pagar ' + totalFormateado + '. ¿Continuar?')) {
        e.preventDefault();
    }
});

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

<?php else: ?>
function abrirSeleccionMetodoPago() {
    alert('Tu carrito está vacío. Agrega productos antes de finalizar la compra.');
}
<?php endif; ?>
</script>