<!-- Sección CATALOGO -->
<div class="catalogo">
    <section id="catalogo-hero">
        <div class="catalogo-header">
            <h1>CATÁLOGO</h1>
            <p>¡Elegí tu camiseta favorita y descubrí nuevos diseños!</p>
        </div>
    </section>

    <!-- Filtro por categoría -->
    <!-- Filtro por categoría estilizado -->
    <section class="catalogo-filtro">
        <h2 class="titulo-categorias">CATEGORÍAS</h2>
        <div class="categoria-buttons">
            <a href="<?= base_url('catalogo') ?>" class="cat-btn <?= $categoriaSeleccionada == '' ? 'active' : '' ?>">Todas</a>
            <?php foreach ($categorias as $cat): ?>
                <a href="<?= base_url('catalogo?categoria=' . $cat['id_categoria']) ?>" 
                class="cat-btn <?= ($categoriaSeleccionada == $cat['id_categoria']) ? 'active' : '' ?>">
                    <?= esc($cat['ct_nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Grid de productos -->
    <section id="catalogo-grid">
        <h2 class="titulo-subseccion">
            <?= $categoriaSeleccionada ? esc($categorias[array_search($categoriaSeleccionada, array_column($categorias, 'id_categoria'))]['ct_nombre']) : 'TODOS LOS PRODUCTOS' ?>
        </h2>
        <div class="catalogo-container">
            <?php if (count($productos) == 0): ?>
                <p class="mensaje-vacio">No hay productos disponibles en esta categoría.</p>
            <?php endif; ?>
            <?php foreach ($productos as $producto): ?>
                <a href="<?= base_url('producto/' . $producto['id']) ?>" class="carta-cat-link">
                    <article class="carta-cat">
                        <figure class="carta-img">
                            <img src="<?= base_url('assets/img/' . trim($producto['imagen'])) ?>" alt="<?= esc($producto['nombre']) ?>">
                        </figure>
                        <div class="carta-descripcion">
                            <h3><?= esc($producto['nombre']) ?></h3>
                            <p class="precio">$<?= number_format($producto['precio'], 2, ',', '.') ?></p>
                        </div>
                    </article>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>
