<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MilCamisetas - Llevá tus colores a todos lados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
        }

        html {
            height: 100%;
            scroll-behavior: smooth;
        }

        body {
            min-height: 100%;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            color: aliceblue;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 40px;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(211, 63, 63, 0.3)), url('assets/img/cruyff.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
            position: relative;
            padding: 80px 20px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(211, 63, 63, 0.1), rgba(155, 2, 2, 0.2));
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero img {
            height: 45vh;
            max-height: 400px;
            filter: drop-shadow(0 10px 30px rgba(211, 63, 63, 0.4));
            transition: transform 0.3s ease;
        }

        .hero img:hover {
            transform: scale(1.05);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            background: linear-gradient(45deg, #ffffff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .hero h4 {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 300;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #d33f3f, #9b0202);
            border: none;
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(211, 63, 63, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(211, 63, 63, 0.4);
            background: linear-gradient(135deg, #9b0202, #d33f3f);
        }

        /* Section Headers */
        .section-header {
            background: linear-gradient(135deg, #424242, #2b2b2b);
            padding: 60px 0;
            text-align: center;
            position: relative;
        }

        .section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(211, 63, 63, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .section-title {
            color: aliceblue;
            font-weight: 800;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            background: linear-gradient(135deg, #d33f3f, #9b0202);
            padding: 25px 40px;
            border-radius: 20px;
            display: inline-block;
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 30px rgba(211, 63, 63, 0.3);
        }

        /* Carousel Section */
        .retro-section {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d), url('assets/img/stadio.jpg');
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
            padding: 80px 0;
            position: relative;
        }

        .retro-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .carousel-with-legends {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    max-width: 1400px;
    margin: 0 auto;
}

.legend-left, .legend-right {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.legend-img {
    width: clamp(180px, 15vw, 280px);
    height: auto;
    border-radius: 20px;
    filter: grayscale(30%) contrast(1.2);
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

.legend-img:hover {
    filter: grayscale(0%) contrast(1.1);
    transform: scale(1.05);
}

#carouselExample {
    flex: 1;
    max-width: 800px;
    min-width: 300px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

/* Media queries para responsive */
@media (max-width: 1200px) {
    .legend-img {
        width: clamp(150px, 12vw, 220px);
    }
    
    .carousel-with-legends {
        gap: 30px;
    }
}

@media (max-width: 992px) {
    .legend-img {
        width: clamp(120px, 10vw, 180px);
    }
    
    .carousel-with-legends {
        gap: 20px;
    }
}

/* Ocultar las imágenes de leyendas en pantallas pequeñas */
@media (max-width: 768px) {
    .legend-left, .legend-right {
        display: none;
    }
    
    .carousel-with-legends {
        justify-content: center;
    }
    
    #carouselExample {
        max-width: 100%;
    }
    
    .carousel-item:not(.active) img {
        display: none;
    }
}

        .carousel-item {
            background: linear-gradient(135deg, rgba(211, 63, 63, 0.1), rgba(155, 2, 2, 0.1));
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .carousel-item h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .carousel-item img {
            max-height: 350px;
            object-fit: contain;
            border-radius: 15px;
            margin-bottom: 30px;
            transition: all 0.4s ease;
        }

        .carousel-item:not(.active) img {
            opacity: 0.6;
            transform: scale(0.95);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background: linear-gradient(135deg, #d33f3f, #9b0202);
            border-radius: 50%;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(211, 63, 63, 0.4);
        }

        .btn-comprar {
            background: linear-gradient(135deg, #d33f3f, #9b0202);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(211, 63, 63, 0.3);
        }

        .btn-comprar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(211, 63, 63, 0.4);
            color: white;
        }

        /* Location Section */
        .ubicacion {
            margin: 0;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .fondo-mapa {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(211, 63, 63, 0.3)), url('assets/img/fotomapa.png');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            color: white;
            text-align: center;
        }

        .fondo-mapa h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .info-extra {
            padding: 80px 20px;
            text-align: center;
            background: white;
        }

        .info-extra h2 {
            font-size: 2.2rem;
            color: #2d2d2d;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .btn-mapa, .btn-vermas {
            background: linear-gradient(135deg, #8B0000, #a30000);
            color: white;
            padding: 15px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(139, 0, 0, 0.3);
        }

        .btn-mapa:hover, .btn-vermas:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 0, 0, 0.4);
            color: white;
        }

        /* Cards Section */
        .destacados-section {
            background: linear-gradient(135deg, #f1f3f4, #e8eaed);
            padding: 80px 0;
        }

        .tarjetas-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            padding: 0 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .tarjeta {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 20px;
            padding: 40px;
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
            position: relative;
            transition: all 0.4s ease;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .tarjeta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(211, 63, 63, 0.2));
            z-index: 1;
        }

        .tarjeta:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        }

        .tarjeta h3, .tarjeta p, .tarjeta .btn-comprar {
            position: relative;
            z-index: 2;
        }

        .tarjeta h3 {
            font-size: 2.2rem;
            color: white;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
        }

        .tarjeta p {
            font-size: 1.1rem;
            color: white;
            font-weight: 500;
            line-height: 1.6;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        /* Quality Badges */
        .calidad {
            background: linear-gradient(135deg, #2b0000, #1a0000);
            padding: 60px 20px;
            text-align: center;
        }

        .badges-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .badge-custom {
            background: linear-gradient(135deg, #d33f3f, #9b0202);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 25px rgba(211, 63, 63, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(211, 63, 63, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                margin-top: 12vh;
                padding: 60px 15px;
            }

            .legend-images {
                flex-direction: column;
                gap: 20px;
            }

            .legend-img {
                display: none;
            }

            .tarjetas-container {
                grid-template-columns: 1fr;
                padding: 0 20px;
            }

            .carousel-item:not(.active) img {
                display: none;
            }

            .badges-container {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <img src="assets/img/ind.png" alt="Remera" class="mb-4">
            <h1 class="slogan">¡Llevá tus colores a todos lados!</h1>
            <h4 class="frase">No esperes más para vestir los colores que amás — encontrá tu camiseta en nuestro catálogo y comprá fácil y rápido.</h4>
            <a href="<?= base_url('catalogo') ?>" class="btn-primary-custom">
                <i class="fas fa-shopping-bag me-2"></i>
                Ir al catálogo
            </a>
        </div>
    </div>

    <!-- Retro Season Section -->
    <div class="section-header">
        <h1 class="section-title">
            <i class="fas fa-history me-3"></i>
            ¡Temporada retro en MilCamisetas!
        </h1>
    </div>

    <div class="retro-section">
    <div class="container">
        <div class="carousel-with-legends">
            <div class="legend-left">
                <img src="assets/img/messi.png" alt="Messi" class="legend-img">
            </div>
            
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <h3><i class="fas fa-star me-2"></i>Argentina 1986 - Titular</h3>
                        <img src="assets/img/retro1.png" class="d-block mx-auto" alt="Argentina 1986">
                        <a href="#" class="btn-comprar">
                            <i class="fas fa-cart-plus me-2"></i>
                            Comprar ahora
                        </a>
                    </div>
                    <div class="carousel-item">
                        <h3><i class="fas fa-star me-2"></i>Brasil 2002 - Titular</h3>
                        <img src="assets/img/retro2.png" class="d-block mx-auto" alt="Brasil 2002">
                        <a href="#" class="btn-comprar">
                            <i class="fas fa-cart-plus me-2"></i>
                            Comprar ahora
                        </a>
                    </div>
                    <div class="carousel-item">
                        <h3><i class="fas fa-star me-2"></i>España 2010 - Titular</h3>
                        <img src="assets/img/retro3.png" class="d-block mx-auto" alt="España 2010">
                        <a href="#" class="btn-comprar">
                            <i class="fas fa-cart-plus me-2"></i>
                            Comprar ahora
                        </a>
                    </div>
                    <div class="carousel-item">
                        <h3><i class="fas fa-star me-2"></i>Francia 1998 - Titular</h3>
                        <img src="assets/img/retro4.png" class="d-block mx-auto" alt="Francia 1998">
                        <a href="#" class="btn-comprar">
                            <i class="fas fa-cart-plus me-2"></i>
                            Comprar ahora
                        </a>
                    </div>
                    <div class="carousel-item">
                        <h3><i class="fas fa-star me-2"></i>Holanda 1974 - Titular</h3>
                        <img src="assets/img/retro5.png" class="d-block mx-auto" alt="Holanda 1974">
                        <a href="#" class="btn-comprar">
                            <i class="fas fa-cart-plus me-2"></i>
                            Comprar ahora
                        </a>
                    </div>
                </div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="legend-right">
                <img src="assets/img/diegote.jpg" alt="Maradona" class="legend-img">
            </div>
        </div>
    </div>
</div>

    <!-- Location Section -->
    <section class="ubicacion">
        <div class="fondo-mapa">
            <h2><i class="fas fa-map-marker-alt me-3"></i>¿Dónde estamos?</h2>
            <a href="https://www.google.com/maps/place/La+Pampa+1569,+Corrientes,+Argentina" target="_blank" class="btn-mapa">
                <i class="fas fa-external-link-alt me-2"></i>
                Ver en Google Maps
            </a>
        </div>
        <div class="info-extra">
            <h2><i class="fas fa-info-circle me-3"></i>¿Querés más información?</h2>
            <a href="nosotros" class="btn-vermas">
                <i class="fas fa-arrow-right me-2"></i>
                Ver más
            </a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <div class="section-header">
        <h1 class="section-title">
            <i class="fas fa-fire me-3"></i>
            ¡Productos destacados de nuestra página!
        </h1>
    </div>

    <div class="destacados-section">
        <div class="tarjetas-container">
            <div class="tarjeta" style="background-image: url('assets/img/novedades.jpeg');">
                <div>
                    <h3><i class="fas fa-bolt me-2"></i>RETROS</h3>
                    <p>Las últimas camisetas de edición limitada, solo por tiempo limitado.</p>
                </div>
                <a href="<?= base_url('catalogo?categoria=1') ?>" class="btn-comprar">
                    <i class="fas fa-eye me-2"></i>
                    Ver más
                </a>
            </div>
            <div class="tarjeta" style="background-image: url('assets/img/lomonaco.png');">
                <div>
                    <h3><i class="fas fa-child me-2"></i>FUTBOL ARGENTINO</h3>
                    <p>Camisetas para los más pequeños, ¡comodidad y estilo!</p>
                </div>
                <a href="<?= base_url('catalogo?categoria=3') ?>" class="btn-comprar">
                    <i class="fas fa-eye me-2"></i>
                    Ver más
                </a>
            </div>
            <div class="tarjeta" style="background-image: url('assets/img/nacional.jpeg');">
                <div>
                    <h3><i class="fas fa-flag me-2"></i>NACIONAL</h3>
                    <p>Las mejores camisetas de equipos nacionales, ¡con el orgullo de siempre!</p>
                </div>
                <a href="<?= base_url('catalogo?categoria=2') ?>" class="btn-comprar">
                    <i class="fas fa-eye me-2"></i>
                    Ver más
                </a>
            </div>
        </div>
    </div>

    <!-- Quality Badges -->
    <div class="calidad">
        <div class="badges-container">
            <span class="badge-custom">
                <i class="fas fa-gem me-2"></i>
                Edición limitada
            </span>
            <span class="badge-custom">
                <i class="fas fa-leaf me-2"></i>
                100% algodón
            </span>
            <span class="badge-custom">
                <i class="fas fa-flag-checkered me-2"></i>
                Producto Nacional
            </span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>