<?php $this->load->view('layout/header', ['title' => 'Mis Ventas - Emetix Vendedor']); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark-custom sidebar collapse" style="min-height: 100vh;">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('seller'); ?>">
                            <i class="fas fa-box me-2"></i> Mis Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="<?= base_url('seller/sales'); ?>">
                            <i class="fas fa-chart-line me-2"></i> Mis Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('profile'); ?>">
                            <i class="fas fa-arrow-left me-2"></i> Volver al Perfil
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4">Reporte de Ventas</h2>

            <!-- Cards Resumen (Calculados al vuelo simple) -->
            <?php
            $total_earnings = 0;
            $items_sold = 0;
            foreach ($sales as $sale) {
                if ($sale->order_status != 'cancelado') { // Solo sumar si no está cancelado
                    $total_earnings += $sale->subtotal;
                    $items_sold += $sale->quantity;
                }
            }
            ?>
            <div class="row mb-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card text-white bg-success mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Ganancias Totales</h5>
                                    <h2 class="fw-bold">$<?= number_format($total_earnings, 2); ?></h2>
                                </div>
                                <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card text-dark bg-warning mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Artículos Vendidos</h5>
                                    <h2 class="fw-bold"><?= $items_sold; ?></h2>
                                </div>
                                <i class="fas fa-shopping-bag fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ventas -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Historial de Transacciones</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Producto</th>
                                    <th>Comprador</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end pe-4">Ganancia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sales)): ?>
                                    <?php foreach ($sales as $sale): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <?= date('d/m/Y', strtotime($sale->sale_date)); ?>
                                                <br>
                                                <small class="text-muted text-nowrap">Ord: #<?= $sale->order_number; ?></small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($sale->product_image): ?>
                                                        <img src="<?= $sale->product_image; ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= $sale->product_name; ?>">
                                                        <?= $sale->product_name; ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="far fa-user-circle me-1 text-muted"></i> <?= $sale->buyer_name; ?>
                                            </td>
                                            <td class="text-center"><?= $sale->quantity; ?></td>
                                            <td class="text-end pe-4 fw-bold text-success">
                                                $<?= number_format($sale->subtotal, 2); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <p class="text-muted mb-0">Aún no tienes ventas registradas.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>