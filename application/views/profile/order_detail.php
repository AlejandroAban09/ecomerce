<?php $this->load->view('layout/header', ['title' => 'Detalle de Orden #' . $order->order_number . ' - Emetix']); ?>
<div class="main-content-with-sidebar">
    <?php $this->load->view('components/sidebar_profile', ['user' => $user, 'current_page' => $current_page]); ?>

    <main class="px-md-4 py-4">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('profile'); ?>">Mi Cuenta</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('profile#orders'); ?>">Mis Pedidos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orden #<?= $order->order_number; ?></li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Orden <span class="text-primary">#<?= $order->order_number; ?></span></h2>
                <p class="text-muted small mb-0">Realizado el <?= date('d/m/Y \a \l\a\s H:i', strtotime($order->created_at)); ?></p>
            </div>
            <a href="<?= base_url('profile#orders'); ?>" class="btn btn-outline-secondary btn-sm-custom">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>

        <div class="row">
            <!-- Detalles de la Orden -->
            <div class="col-lg-8">
                <div class="card card-standard mb-4 border-0">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-box-open me-2 text-primary"></i>Productos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 border-0 text-secondary small text-uppercase">Producto</th>
                                        <th class="text-center border-0 text-secondary small text-uppercase">Precio</th>
                                        <th class="text-center border-0 text-secondary small text-uppercase">Cant.</th>
                                        <th class="text-end pe-4 border-0 text-secondary small text-uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order->items as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <?php if (isset($item->product_image) && $item->product_image): ?>
                                                        <img src="<?= $item->product_image; ?>" class="rounded me-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center text-muted border" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold text-dark"><?= $item->product_name; ?></h6>
                                                        <!-- <small class="text-muted">Variante: N/A</small> -->
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-muted">$<?= number_format($item->price, 2); ?></td>
                                            <td class="text-center fw-bold"><?= $item->quantity; ?></td>
                                            <td class="text-end pe-4 fw-bold text-dark">$<?= number_format($item->subtotal, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="col-lg-4">
                <div class="card card-standard mb-4 border-0">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Resumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Estado del Pedido</span>
                            <?php
                            $status_class = 'bg-secondary';
                            switch ($order->status) {
                                case 'completado':
                                    $status_class = 'bg-success';
                                    break;
                                case 'pendiente':
                                    $status_class = 'bg-warning text-dark';
                                    break;
                                case 'cancelado':
                                    $status_class = 'bg-danger';
                                    break;
                                case 'en_transito':
                                    $status_class = 'bg-info';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $status_class; ?>"><?= ucfirst($order->status); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Método de Pago</span>
                            <span class="fw-medium">Tarjeta / PayPal</span> <!-- Simulado, ajustar con $order->payment_method si existe -->
                        </div>

                        <hr class="my-3 border-secondary opacity-10">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h6 mb-0 text-muted">Total Pagado</span>
                            <span class="h4 fw-bold text-primary mb-0">$<?= number_format($order->total_amount, 2); ?></span>
                        </div>
                    </div>
                    <!-- Botón de soporte mejorado -->
                    <div class="card-footer bg-light border-0 p-3">
                        <button class="btn btn-outline-dark w-100 btn-sm-custom">
                            <i class="fas fa-headset me-2"></i> ¿Necesitas ayuda?
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('layout/footer'); ?>