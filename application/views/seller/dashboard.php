<?php 
$this->load->view('layout/header', ['title' => 'Panel de Vendedor - Emetix']); 
$current_page = 'seller_dashboard';
?>

<div class="main-content-with-sidebar">
    <!-- Sidebar Unificado -->
    <?php $this->load->view('components/sidebar_profile', ['user' => $user, 'current_page' => $current_page]); ?>

    <!-- Main Content -->
    <main class="px-md-4 py-4">
        <!-- Breadcrumb Navigation -->
        <?php $this->load->view('components/breadcrumb', ['current_page' => $current_page]); ?>
        
        <!-- Sistema de Alertas Unificado -->
        <?php $this->load->view('components/flash_messages'); ?>
<div class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="fas fa-box me-2 text-primary"></i>Mis Productos
                </h3>
                <a href="<?= base_url('seller/create_product'); ?>" class="btn btn-custom btn-primary-custom">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>

            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-box-open fa-5x text-muted opacity-25"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aún no tienes productos.</h4>
                    <p class="text-muted mb-4">¡Publica tu primer artículo para empezar a vender!</p>
                    <a href="<?= base_url('seller/create_product'); ?>" class="btn btn-custom btn-outline-primary">
                        <i class="fas fa-plus-circle me-2"></i>Crear Producto
                    </a>
                </div>
            <?php else: ?>
                <!-- Estadísticas rápidas -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card-standard text-center p-3">
                            <div class="text-primary fs-2 mb-2">
                                <i class="fas fa-box"></i>
                            </div>
                            <h5 class="mb-0"><?= count($products) ?></h5>
                            <small class="text-muted">Total Productos</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card-standard text-center p-3">
                            <div class="text-success fs-2 mb-2">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5 class="mb-0"><?= count(array_filter($products, function($p) { return $p->status == 'active'; })) ?></h5>
                            <small class="text-muted">Activos</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card-standard text-center p-3">
                            <div class="text-warning fs-2 mb-2">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="mb-0"><?= count(array_filter($products, function($p) { return $p->stock < 5; })) ?></h5>
                            <small class="text-muted">Stock Bajo</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card-standard text-center p-3">
                            <div class="text-danger fs-2 mb-2">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h5 class="mb-0"><?= count(array_filter($products, function($p) { return $p->stock == 0; })) ?></h5>
                            <small class="text-muted">Agotados</small>
                        </div>
                    </div>
                </div>

                <!-- Tabla de productos -->
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($product->image_url): ?>
                                                <img src="<?= $product->image_url ?>" alt="<?= htmlspecialchars($product->name) ?>" class="table-img me-3">
                                            <?php else: ?>
                                                <div class="table-img me-3 d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0 text-truncate"><?= htmlspecialchars($product->name) ?></h6>
                                                <small class="text-muted">ID: <?= $product->id ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-bold">$<?= number_format($product->price, 2) ?></td>
                                    <td>
                                        <?php if ($product->stock == 0): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Agotado
                                            </span>
                                        <?php elseif ($product->stock < 5): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle me-1"></i><?= $product->stock ?> (Bajo)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i><?= $product->stock ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = ($product->status == 'active') ? 'bg-success' : 'bg-secondary';
                                        $status_icon = ($product->status == 'active') ? 'check' : 'pause';
                                        $status_label = ($product->status == 'active') ? 'Activo' : ucfirst($product->status);
                                        ?>
                                        <span class="badge <?= $status_class; ?>">
                                            <i class="fas fa-<?= $status_icon ?> me-1"></i><?= $status_label; ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('seller/edit_product/' . $product->id); ?>" 
                                               class="btn btn-sm btn-custom btn-outline-primary" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete('<?= base_url('seller/delete_product/' . $product->id); ?>', '<?= htmlspecialchars($product->name); ?>')" 
                                                    class="btn btn-sm btn-custom btn-outline-danger" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php $this->load->view('layout/footer'); ?>