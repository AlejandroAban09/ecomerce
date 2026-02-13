<?php
$header_data = [
    'meta_title' => isset($meta_title) ? $meta_title : $product->name . ' - Emetix',
    'meta_description' => isset($meta_description) ? $meta_description : '',
    'meta_keywords' => isset($meta_keywords) ? $meta_keywords : '',
    'og_image' => isset($og_image) ? $og_image : ''
];
$this->load->view('layout/header', $header_data);
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('shop'); ?>">Tienda</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $product->name; ?></li>
        </ol>
    </nav>

    <div class="row g-5 mt-2">
        <!-- Imagen del Producto -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3">
                <?php if (!empty($product->image_url)): ?>
                    <img src="<?= $product->image_url; ?>" class="img-fluid rounded mx-auto d-block" style="max-height: 500px; object-fit: contain;" alt="<?= $product->name; ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center text-muted rounded" style="height: 400px;">
                        <i class="fas fa-image fa-5x"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detalles del Producto -->
        <div class="col-md-6">
            <h1 class="fw-bold mb-2"><?= $product->name; ?></h1>

            <div class="mb-3">
                <span class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </span>
                <span class="text-muted ms-2">(4.8 reseñas)</span>
            </div>

            <div class="h2 fw-bold text-primary mb-4">
                $<?= number_format($product->price, 2); ?>
            </div>

            <div class="mb-4">
                <p class="text-muted" style="white-space: pre-line;"><?= $product->description; ?></p>
            </div>

            <!-- Seller Info -->
            <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                <div class="me-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block">Vendido por</small>
                    <strong><?= $product->store_name ? $product->store_name : $product->seller_name; ?></strong>
                </div>
            </div>

            <!-- Stock & Actions -->
            <div class="mb-4">
                <?php if ($product->stock > 0): ?>
                    <span class="badge bg-success mb-3">En Stock (<?= $product->stock; ?> disponibles)</span>

                    <form action="<?= base_url('cart/add'); ?>" method="post">
                        <input type="hidden" name="product_id" value="<?= $product->id; ?>">
                        <div class="d-flex gap-3 align-items-center">
                            <!-- Selector de Cantidad -->
                            <div class="input-group" style="width: 140px;">
                                <button class="btn btn-outline-dark" type="button" id="btn-minus"><i class="fas fa-minus"></i></button>
                                <input type="number" name="qty" class="form-control text-center border-dark fw-bold" value="1" id="qty-input" readonly>
                                <button class="btn btn-outline-dark" type="button" id="btn-plus"><i class="fas fa-plus"></i></button>
                            </div>

                            <!-- Botón de Compra -->
                            <button type="submit" class="btn btn-primary-custom flex-grow-1 py-2 shadow-sm">
                                <i class="fas fa-cart-plus me-2"></i> Añadir al Carrito
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <span class="badge bg-danger mb-3">Agotado</span>
                    <button class="btn btn-secondary w-100" disabled>No Disponible</button>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark btn-sm"><i class="far fa-heart me-1"></i> Wishlist</button>
                <button class="btn btn-outline-dark btn-sm"><i class="fas fa-share-alt me-1"></i> Compartir</button>
            </div>
        </div>
    </div>
</div>

<!-- Recently Viewed Section -->
<?php if (!empty($recently_viewed)): ?>
    <div class="container my-5 border-top pt-5">
        <h3 class="mb-4">Visto Recientemente</h3>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
            <?php foreach ($recently_viewed as $rv_product): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="<?= base_url('product/' . $rv_product->slug) ?>" class="text-decoration-none">
                            <?php if (!empty($rv_product->image_url)): ?>
                                <img src="<?= $rv_product->image_url ?>" class="card-img-top p-2" style="height: 150px; object-fit: contain;" alt="<?= $rv_product->name ?>">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 150px;">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-2 text-center">
                                <h6 class="card-title text-dark text-truncate small mb-1"><?= $rv_product->name ?></h6>
                                <span class="text-primary fw-bold small">$<?= number_format($rv_product->price, 2) ?></span>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
    // Script simple para input de cantidad
    document.addEventListener('DOMContentLoaded', function() {
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const qtyInput = document.getElementById('qty-input');
        const maxStock = <?= $product->stock; ?>;

        if (btnMinus && btnPlus && qtyInput) {
            btnMinus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val > 1) qtyInput.value = val - 1;
            });

            btnPlus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val < maxStock) qtyInput.value = val + 1;
            });
        }
    });
</script>

<?php $this->load->view('layout/footer'); ?>

<!-- Schema.org Product Data -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?= html_escape($product->name) ?>",
        "image": [
            "<?= $product->image_url ?>"
        ],
        "description": "<?= html_escape(str_replace('"', '\"', strip_tags($product->description))) ?>",
        "sku": "<?= $product->id ?>",
        "brand": {
            "@type": "Brand",
            "name": "Emetix"
        },
        "offers": {
            "@type": "Offer",
            "url": "<?= current_url() ?>",
            "priceCurrency": "MXN",
            "price": "<?= $product->price ?>",
            "availability": "<?= ($product->stock > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
            "seller": {
                "@type": "Organization",
                "name": "<?= isset($product->store_name) ? html_escape($product->store_name) : 'Emetix' ?>"
            }
        }
    }
</script>