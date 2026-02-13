<?php
$header_data = [
    'meta_title' => isset($meta_title) ? $meta_title : NULL,
    'meta_description' => isset($meta_description) ? $meta_description : NULL,
    'meta_keywords' => isset($meta_keywords) ? $meta_keywords : NULL
];
$this->load->view('layout/header', $header_data);
?>

<!-- Hero Section -->
<section class="hero-section text-white d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <span class="hero-subtitle">Descuento Especial</span>
                <h2 class="display-3 fw-bold mb-3">Latest Audio System<br> offer <span class="text-primary">20% off</span></h2>
                <p class="lead mb-4 text-white-50">Descubre la mejor calidad de sonido con nuestra nueva colección.</p>
                <a href="<?= base_url('shop') ?>" class="btn btn-primary-custom rounded-pill px-5 py-3">COMPRAR AHORA</a>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <!-- Placeholder para imagen de héroe -->
                <img src="https://res.cloudinary.com/demo/image/upload/v1691684340/samples/ecommerce/analog-classic.jpg" alt="Producto Hero" class="img-fluid rounded shadow-lg" style="transform: rotate(-5deg); border: 5px solid rgba(255,255,255,0.1);">
            </div>
        </div>
    </div>
</section>

<!-- Features Band -->
<section class="features-band bg-white">
    <div class="container">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start feature-item">
                <i class="fas fa-truck text-primary"></i>
                <div class="feature-text">
                    <h5>ENVÍO GRATIS</h5>
                    <p>En pedidos mayores a $100</p>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0 d-flex align-items-center justify-content-center justify-content-md-start feature-item">
                <i class="fas fa-headset text-primary"></i>
                <div class="feature-text">
                    <h5>SOPORTE 24/7</h5>
                    <p>Atención al cliente experta</p>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start feature-item">
                <i class="fas fa-undo text-primary"></i>
                <div class="feature-text">
                    <h5>GARANTÍA DE DEVOLUCIÓN</h5>
                    <p>30 días para cambios</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promo Grid -->
<section class="promo-grid">
    <div class="container">
        <div class="row g-4">
            <!-- Card Grande Izquierda -->
            <div class="col-lg-4 col-md-6">
                <div class="promo-card bg-dark-custom h-100 d-flex flex-column justify-content-center">
                    <span class="promo-tag">Sale Up To 30% Off</span>
                    <h3 class="mb-4">Latest Sound System</h3>
                    <a href="#" class="btn btn-outline-light btn-sm w-50">VER OFERTA</a>
                    <img src="https://res.cloudinary.com/demo/image/upload/v1691684340/samples/ecommerce/leather-bag-gray.jpg" class="img-fluid mt-3 opacity-50" style="mix-blend-mode: overlay;">
                </div>
            </div>

            <!-- Columna Central -->
            <div class="col-lg-4 col-md-6">
                <div class="row g-4 h-100">
                    <div class="col-12 h-50">
                        <div class="promo-card bg-dark-custom mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="text-primary small fw-bold">20% Off</span>
                                    <h4>Smart Watch</h4>
                                    <a href="#" class="text-warning small text-decoration-none">Comprar ></a>
                                </div>
                                <i class="fas fa-clock fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 h-50">
                        <div class="promo-card bg-dark-custom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4>Smart Speaker</h4>
                                    <p class="small text-white-50">Control por voz</p>
                                </div>
                                <i class="fas fa-microphone-alt fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Grande Derecha -->
            <div class="col-lg-4 col-md-12">
                <div class="row g-4 h-100">
                    <div class="col-md-6 col-lg-12 h-lg-50">
                        <div class="promo-card bg-dark-custom mb-lg-4">
                            <h4>Tablet Computer</h4>
                            <p class="small text-white-50 mb-0">Potencia en tus manos</p>
                            <i class="fas fa-tablet-alt fs-1 text-white-50 mt-3 d-block text-end"></i>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12 h-lg-50">
                        <div class="promo-card bg-dark-custom">
                            <span class="text-primary">Game Controller</span>
                            <h3>Para Profesionales</h3>
                            <a href="#" class="text-warning small text-decoration-none text-uppercase fw-bold">Ver más</a>
                            <i class="fas fa-gamepad fs-1 text-white-50 mt-3 d-block text-end"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Products -->
<section class="py-5">
    <div class="container">
        <div class="section-header">
            <h3>Top Products</h3>
            <ul class="nav nav-pills small" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill text-dark fw-bold" data-bs-toggle="pill">Latest</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill text-muted" data-bs-toggle="pill">Best Seller</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill text-muted" data-bs-toggle="pill">Featured</button>
                </li>
            </ul>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
            <?php if (!empty($latest_products)): ?>
                <?php foreach ($latest_products as $product): ?>
                    <div class="col">
                        <div class="product-card h-100">
                            <!-- Badge Stock -->
                            <?php if ($product->stock < 5 && $product->stock > 0): ?>
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3">¡Pocos!</span>
                            <?php elseif ($product->stock == 0): ?>
                                <span class="badge bg-secondary position-absolute top-0 start-0 m-3">Agotado</span>
                            <?php else: ?>
                                <span class="badge bg-success position-absolute top-0 start-0 m-3">Nuevo</span>
                            <?php endif; ?>

                            <!-- Imagen -->
                            <?php if (!empty($product->image_url)): ?>
                                <img src="<?= $product->image_url ?>" class="product-img img-fluid" alt="<?= $product->name ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted product-img" style="height: 180px;">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Rating (Hardcoded por ahora) -->
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>

                            <h5 class="product-title text-truncate" title="<?= $product->name ?>"><?= $product->name ?></h5>
                            <div class="product-price">
                                $<?= number_format($product->price, 2) ?>
                            </div>

                            <a href="<?= base_url('product/' . $product->slug) ?>" class="btn btn-sm btn-outline-primary mt-3 w-100 stretched-link">Ver Detalles</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aún no hay productos destacados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $this->load->view('layout/footer'); ?>