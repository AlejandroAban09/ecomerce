<?php $this->load->view('layout/header', ['title' => 'Carrito de Compras - Emetix']); ?>

<div class="container my-5">
    <h2 class="mb-4">Carrito de Compras</h2>

    <?php if ($this->cart->total_items() > 0): ?>
        <div class="row">
            <!-- Lista de Productos -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm align-middle">
                    <div class="table-responsive">
                        <form action="<?= base_url('cart/update'); ?>" method="post">
                            <table class="table table-align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-4" style="width: 40%">Producto</th>
                                        <th class="py-3 text-center" style="width: 20%">Precio</th>
                                        <th class="py-3 text-center" style="width: 20%">Cantidad</th>
                                        <th class="py-3 text-end pe-4" style="width: 20%">Total</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->cart->contents() as $items): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <?php if ($items['options']['image']): ?>
                                                        <img src="<?= $items['options']['image']; ?>" alt="Img" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0 text-truncate" style="max-width: 200px;"><?= $items['name']; ?></h6>
                                                        <small class="text-muted"><a href="<?= base_url('product/' . $items['options']['slug']); ?>" class="text-decoration-none text-secondary">Ver detalle</a></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">$<?= number_format($items['price'], 2); ?></td>
                                            <td class="text-center">
                                                <input type="number" name="cart[<?= $items['rowid']; ?>]" value="<?= $items['qty']; ?>" class="form-control form-control-sm mx-auto text-center" style="width: 70px;" min="1">
                                            </td>
                                            <td class="text-end pe-4 fw-bold">$<?= number_format($items['subtotal'], 2); ?></td>
                                            <td class="text-end">
                                                <a href="<?= base_url('cart/remove/' . $items['rowid']); ?>" class="text-danger"><i class="fas fa-times"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between p-3 bg-light">
                                <a href="<?= base_url('shop'); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Seguir Comprando</a>
                                <button type="submit" class="btn btn-outline-primary"><i class="fas fa-sync-alt me-2"></i> Actualizar Carrito</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resumen de Orden -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">$<?= number_format($this->cart->total(), 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Envío</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total</span>
                            <span class="h5 fw-bold text-primary">$<?= number_format($this->cart->total(), 2); ?></span>
                        </div>
                        <a href="<?= base_url('checkout'); ?>" class="btn btn-primary-custom w-100 py-2 fw-bold">Proceder al Pago</a>

                        <div class="mt-4 text-center">
                            <small class="text-muted">Aceptamos</small>
                            <div class="fs-4 text-secondary mt-1">
                                <i class="fab fa-cc-visa mx-1"></i>
                                <i class="fab fa-cc-mastercard mx-1"></i>
                                <i class="fab fa-cc-paypal mx-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted opacity-25"></i>
            </div>
            <h3 class="text-muted">Tu carrito está vacío</h3>
            <p class="mb-4">Parece que aún no has agregado nada.</p>
            <a href="<?= base_url('shop'); ?>" class="btn btn-primary-custom px-5">Ir a la Tienda</a>
        </div>
    <?php endif; ?>
</div>

<?php $this->load->view('layout/footer'); ?>