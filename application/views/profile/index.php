<?php 
$this->load->view('layout/header', ['title' => 'Mi Perfil - Emetix']); 
$current_page = 'account';
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

<!-- Contenido de las Tabs -->
<div class="tab-content" id="profileTabsContent">

    <!-- Pestaña Mi Cuenta -->
    <div class="tab-pane fade show active" id="account" role="tabpanel">
        <div class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Mi Información</h3>
                <?php if (!$user->is_seller): ?>
                    <button type="button" class="btn btn-custom btn-primary-custom" onclick="showCreateStoreModal()">
                        <i class="fas fa-store"></i>
                        ¡Quiero Vender!
                    </button>
                <?php endif; ?>
            </div>

            <form id="profileForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Nombre de Usuario</label>
                            <input type="text" class="form-control-custom bg-light" value="<?= htmlspecialchars($user->username); ?>" readonly>
                            <small class="form-text-custom">El nombre de usuario no se puede modificar</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Email</label>
                            <input type="email" class="form-control-custom bg-light" value="<?= htmlspecialchars($user->email); ?>" readonly>
                            <small class="form-text-custom">Contacta soporte para cambiar tu email</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Nombre Completo</label>
                            <input type="text" name="full_name" class="form-control-custom" value="<?= htmlspecialchars($user->full_name); ?>" placeholder="Ej. Juan Pérez">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Teléfono</label>
                            <input type="tel" name="phone" class="form-control-custom" value="<?= htmlspecialchars($user->phone); ?>" placeholder="Ej. 555-1234">
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-custom btn-primary-custom">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pestaña Mis Pedidos -->
    <div class="tab-pane fade" id="orders" role="tabpanel">
        <div class="form-section">
            <h3 class="fw-bold mb-4">Historial de Compras</h3>

            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No. Orden</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="fw-bold text-primary">#<?= htmlspecialchars($order->order_number); ?></td>
                                    <td><?= date('d/m/Y', strtotime($order->created_at)); ?></td>
                                    <td class="fw-bold">$<?= number_format($order->total_amount, 2); ?></td>
                                    <td>
                                        <?php
                                        $status_badge = 'bg-secondary';
                                        $status_icon = 'clock';
                                        if ($order->status == 'completado') { 
                                            $status_badge = 'bg-success'; 
                                            $status_icon = 'check-circle';
                                        }
                                        if ($order->status == 'pendiente') { 
                                            $status_badge = 'bg-warning text-dark'; 
                                            $status_icon = 'clock';
                                        }
                                        if ($order->status == 'cancelado') { 
                                            $status_badge = 'bg-danger'; 
                                            $status_icon = 'times-circle';
                                        }
                                        ?>
                                        <span class="badge <?= $status_badge; ?>">
                                            <i class="fas fa-<?= $status_icon ?> me-1"></i>
                                            <?= ucfirst(htmlspecialchars($order->status)); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('profile/view_order/' . $order->id); ?>" class="btn btn-sm btn-custom btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart fa-4x text-muted opacity-25"></i>
                    </div>
                    <h5 class="text-muted">No tienes pedidos recientes.</h5>
                    <p class="text-muted">Explora nuestro catálogo y encuentra algo increíble.</p>
                    <a href="<?= base_url('shop'); ?>" class="btn btn-custom btn-primary-custom mt-3">
                        <i class="fas fa-shopping-bag me-2"></i>Ir a Comprar
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
        </main>
    </div>
</div>

    </main>
</div>

<!-- Modal Crear Tienda Mejorado -->
<div class="modal fade" id="createStoreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-store me-2 text-primary"></i>Configurar Tienda
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?= form_open('profile/become_seller', ['id' => 'createStoreForm']); ?>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-lg bg-primary d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-store fa-2x text-dark"></i>
                    </div>
                    <p class="text-muted mb-0">¡Dale un nombre a tu nuevo emprendimiento!</p>
                </div>
                
                <div class="form-group-custom mb-3">
                    <label for="store_name" class="form-label-custom fw-bold">
                        <i class="fas fa-tag me-1"></i>Nombre de la Tienda
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-store"></i>
                        </span>
                        <input type="text" class="form-control-custom" id="store_name" name="store_name" 
                               required placeholder="Ej. TecnoStore" maxlength="50">
                    </div>
                    <small class="form-text-custom">Será visible para todos tus clientes. Máximo 50 caracteres.</small>
                </div>
                
                <div class="form-check bg-light p-3 rounded">
                    <input class="form-check-input" type="checkbox" required id="terms">
                    <label class="form-check-label small" for="terms">
                        Acepto los términos y condiciones de venta de Emetix.
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-custom btn-primary-custom px-4">
                    <i class="fas fa-rocket me-2"></i>Crear Tienda
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- JavaScript para formularios -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validar formulario de perfil
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validación básica
            const fullName = profileForm.full_name.value.trim();
            const phone = profileForm.phone.value.trim();
            
            if (!fullName) {
                showAlert('error', 'Por favor ingresa tu nombre completo');
                return;
            }
            
            if (!phone) {
                showAlert('error', 'Por favor ingresa tu teléfono');
                return;
            }
            
            // Simular envío (en producción harías una llamada AJAX)
            showAlert('success', 'Información actualizada correctamente');
        });
    }
    
    // Validar formulario de tienda
    const storeForm = document.getElementById('createStoreForm');
    if (storeForm) {
        storeForm.addEventListener('submit', function(e) {
            const storeName = storeForm.store_name.value.trim();
            
            if (storeName.length < 3) {
                showAlert('error', 'El nombre de la tienda debe tener al menos 3 caracteres');
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>

<?php $this->load->view('layout/footer'); ?>