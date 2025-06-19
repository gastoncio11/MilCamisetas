<?php $cart = \Config\Services::cart(); ?>
<h1 class="text-center">Carrito de compras</h1><a href="productos" class="btn btn-success" role="button"></continuar comprando </a>
<?php if ($cart->contents() == NULL) { ?>
    <h2 class="text-center alert alert-danger"> Carrito esta vacio</h2 <?php } ?>
    <table id="mytable" class="table table-bordered table-striped">
    <?php if ($cart1 = $cart->contents()): ?>
    <thead>
    <td>Nº item</td>
    <td>Nombre</td>
    <td>Precio</td>
    <td>Cantidad</td>
    <td>Subtotal</td>
    <td>Acción</td>
</thead>

<tbody>
<?php
    $total = 0;
    $i = 1;
    foreach ($cart->contents() as $item): ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= esc($item['name']) ?></td>
    <td>$<?= number_format($item['price'], 2) ?></td>
    <td><?= $item['qty'] ?></td>
    <td>$<?= number_format($item['subtotal'], 2); $total += $item['subtotal']; ?></td>
    <td><?= anchor('eliminar_item/'.$item['rowid'], 'Eliminar', 'class="btn btn-danger btn-sm"') ?></td>
</tr>
<?php endforeach; ?>
    <tr>
        <td colspan="4" class="text-right"><strong>Total Compra:</strong></td>
        <td>$<?= number_format($total, 2) ?></td>
        <td colspan="2">
            <a href="<?= base_url('vaciar_carrito/all') ?>" class="btn btn-warning btn-sm">Vaciar carrito</a>
            <a href="<?= base_url('ventas') ?>" class="btn btn-success btn-sm">Ordenar compra</a>
        </td>
    </tr>
</tbody>
</table>