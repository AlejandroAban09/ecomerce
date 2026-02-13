<?php $this->load->view('layout/header', ['title' => 'Tienda - Emetix']); ?>

<div class="container my-5">
    <!-- Header de la Tienda -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2>Nuestra Colección</h2>
            <?php if (!empty($search)): ?>
                <p class="text-muted">Resultados para: "<strong><?= html_escape($search); ?></strong>"</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <form action="<?= base_url('shop'); ?>" method="get" class="d-flex">
                <input type="text" name="q" class="form-control me-2" placeholder="Buscar productos..." value="<?= isset($search) ? html_escape($search) : ''; ?>">
                <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <!-- Grid de Productos -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
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
                <div class="py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No encontramos productos.</h4>
                    <p>Intenta con otra búsqueda o regresa a ver todo.</p>
                    <a href="<?= base_url('shop'); ?>" class="btn btn-primary-custom mt-2">Ver todo el catálogo</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <div class="row">
        <div class="col-12">
            <?= $pagination; ?>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>