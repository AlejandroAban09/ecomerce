<?php
// seller/sales_content.php - Contenido específico para la vista de ventas
// El Layout principal (dashboard_template) ya ha cargado header, sidebar y footer
?>

<div>
    <h2 class="mb-4">Reporte de Ventas</h2>

    <!-- Cards Resumen -->
    <?php
    $total_earnings = 0;
    $items_sold = 0;

    // Calcular totales si hay ventas
    if (!empty($sales)) {
        foreach ($sales as $sale) {
            if ($sale->order_status != 'cancelado') {
                $total_earnings += $sale->subtotal;
                $items_sold += $sale->quantity;
            }
        }
    }
    ?>
    <div class="row mb-4 fade-in">
        <div class="col-md-6 col-lg-4">
            <div class="card bg-success text-white mb-3 shadow border-0 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white-50 mb-0">Ganancias Totales</h5>
                            <h2 class="fw-bold mt-2 display-6">$<?= number_format($total_earnings, 2); ?></h2>
                        </div>
                        <div class="icon-circle bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card bg-warning text-dark mb-3 shadow border-0 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-dark-50 mb-0">Artículos Vendidos</h5>
                            <h2 class="fw-bold mt-2 display-6"><?= $items_sold; ?></h2>
                        </div>
                        <div class="icon-circle bg-dark bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-shopping-bag fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="card shadow border-0 mb-4 fade-in" style="animation-delay: 0.1s;">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Historial de Transacciones</h5>
                <button class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="fas fa-download me-1"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4 border-0">Fecha</th>
                            <th class="border-0">Producto</th>
                            <th class="border-0">Comprador</th>
                            <th class="text-center border-0">Cant.</th>
                            <th class="text-end pe-4 border-0">Ganancia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sales)): ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold"><?= date('d M, Y', strtotime($sale->sale_date)); ?></span>
                                            <small class="text-muted">#<?= $sale->order_number; ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($sale->product_image): ?>
                                                <img src="<?= $sale->product_image; ?>" class="rounded-3 shadow-sm me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-3 shadow-sm me-3 d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-image fa-lg"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <span class="d-block fw-medium text-dark text-truncate" style="max-width: 200px;"><?= $sale->product_name; ?></span>
                                                <span class="badge bg-light text-dark border"><i class="fas fa-tag me-1 text-muted"></i><?= $sale->category_name ?? 'General'; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <?= strtoupper(substr($sale->buyer_name, 0, 1)); ?>
                                            </div>
                                            <span><?= $sale->buyer_name; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill"><?= $sale->quantity; ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="fw-bold text-success fs-6">$<?= number_format($sale->subtotal, 2); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-50 mb-3">
                                        <i class="fas fa-receipt fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Aún no tienes ventas registradas</h5>
                                    <p class="text-muted small">Tus ventas aparecerán aquí una vez que los clientes compren tus productos.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-0">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
    .card-hover {
        transition: transform 0.2s;
    }

    .card-hover:hover {
        transform: translateY(-5px);
    }

    .avatar-circle {
        font-weight: bold;
    }
</style>