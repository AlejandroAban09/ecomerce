<?php $this->load->view('layout/header', ['title' => 'Checkout - Emetix']); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white p-4">
                    <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i> Confirmar Pedido</h3>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Resumen de tu Compra</h5>

                    <ul class="list-group mb-4">
                        <?php foreach ($cart_contents as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><?= $item['name']; ?></h6>
                                    <small class="text-muted">Cantidad: <?= $item['qty']; ?></small>
                                </div>
                                <span class="fw-bold">$<?= number_format($item['subtotal'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <span class="fw-bold h5 mb-0">Total a Pagar</span>
                            <span class="fw-bold h5 mb-0 text-primary">$<?= number_format($total, 2); ?></span>
                        </li>
                    </ul>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Al hacer clic en "Pagar Ahora", se procesará tu pedido y **se descontará el stock** de los productos.
                    </div>

                    <form action="<?= base_url('checkout/place_order'); ?>" method="post">
                        <!-- Aquí irían inputs de envío, tarjeta, etc. -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                Pagar Ahora <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <a href="<?= base_url('cart'); ?>" class="btn btn-outline-secondary">Volver al Carrito</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>