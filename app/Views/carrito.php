<?php $cart = \Config\Services::cart(); ?>

<div class="carrito-container">
    <div class="carrito-header">
        <h1>Carrito de Compras</h1>
        <a href="<?= base_url('catalogo') ?>" class="btn-continuar">
            <i class="fas fa-arrow-left"></i> Continuar Comprando
        </a>
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

    <?php if ($cart->contents() == NULL): ?>
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
                $total = 0;
                $i = 1;
                foreach ($cart->contents() as $item): ?>
                    <div class="item-carrito">
                        <div class="item-numero">
                            <span class="numero"><?= $i++ ?></span>
                        </div>
                        <div class="item-info">
                            <h3 class="item-nombre"><?= esc($item['name']) ?></h3>
                            <div class="item-detalles">
                                <span class="precio-unitario">$<?= number_format($item['price'], 2) ?></span>
                                <span class="separador">×</span>
                                <span class="cantidad"><?= $item['qty'] ?></span>
                            </div>
                        </div>
                        <div class="item-subtotal">
                            <span class="subtotal-valor">$<?= number_format($item['subtotal'], 2); $total += $item['subtotal']; ?></span>
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
                        <form action="<?= base_url('guardar-venta') ?>" method="post" style="display:inline;">
                            <button type="submit" class="btn-ordenar">
                                <i class="fas fa-check"></i> Finalizar Compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
