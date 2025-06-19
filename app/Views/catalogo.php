<!-- Sección CATALOGO -->
<div class="catalogo">
    <section id="catalogo-hero">
        <div class="catalogo-header">
            <h1>CATALOGO</h1>
            <p>¡Elegí tu camiseta favorita y descubrí nuevos diseños!</p>
        </div>
    </section>

    <section id="catalogo-grid">
        <h2 class="titulo-subseccion">RETROS!</h2>
        <div class="catalogo-container">
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