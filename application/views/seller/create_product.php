<?php 
$this->load->view('layout/header', ['title' => 'Nuevo Producto - Emetix']); 
$current_page = 'create_product';
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
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Publicar Nuevo Producto
                </h3>
                <a href="<?= base_url('seller'); ?>" class="btn btn-custom btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Panel
                </a>
            </div>

<!-- Formulario Multipart -->
                    <?= form_open_multipart('seller/create_product', ['id' => 'createProductForm']); ?>

                    <div class="form-group-custom">
                        <label for="name" class="form-label-custom">
                            <i class="fas fa-tag me-1"></i>Nombre del Producto
                        </label>
                        <input type="text" class="form-control-custom" id="name" name="name" 
                               value="<?= set_value('name'); ?>" 
                               placeholder="Ej. Audífonos Bluetooth Sony" required autofocus>
                        <small class="form-text-custom">Nombre claro y descriptivo para tus clientes</small>
                        <div class="invalid-feedback-custom"><?= form_error('name'); ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="price" class="form-label-custom">
                                    <i class="fas fa-dollar-sign me-1"></i>Precio
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control-custom" id="price" 
                                           name="price" value="<?= set_value('price'); ?>" 
                                           placeholder="0.00" required>
                                </div>
                                <div class="invalid-feedback-custom"><?= form_error('price'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label for="stock" class="form-label-custom">
                                    <i class="fas fa-boxes me-1"></i>Stock Disponible
                                </label>
                                <input type="number" class="form-control-custom" id="stock" 
                                       name="stock" value="<?= set_value('stock', 1); ?>" required>
                                <small class="form-text-custom">Cantidad de unidades disponibles</small>
                                <div class="invalid-feedback-custom"><?= form_error('stock'); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="description" class="form-label-custom">
                            <i class="fas fa-align-left me-1"></i>Descripción Detallada
                        </label>
                        <textarea class="form-control-custom" id="description" name="description" 
                                  rows="5" placeholder="Describe las características principales..." required><?= set_value('description'); ?></textarea>
                        <small class="form-text-custom">Sé específico sobre las características y beneficios</small>
                        <div class="invalid-feedback-custom"><?= form_error('description'); ?></div>
                    </div>

                    <div class="form-group-custom">
                        <label for="image" class="form-label-custom">
                            <i class="fas fa-image me-1"></i>Imagen Principal
                        </label>
                        <input class="form-control-custom" type="file" id="image" name="image" accept="image/*" required>
                        <small class="form-text-custom">Sube una imagen de alta calidad (JPG, PNG). Se subirá a Cloudinary.</small>
                        <div class="invalid-feedback-custom"><?= form_error('image'); ?></div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= base_url('seller'); ?>" class="btn btn-custom btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-custom btn-primary-custom">
                            <i class="fas fa-upload me-2"></i>Publicar Producto
                        </button>
                    </div>
                    <?= form_close(); ?>
                </div>
        </div>
    </main>
</div>

<!-- JavaScript de validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createProductForm');
    const fileInput = document.getElementById('image');
    
    // Preview de imagen
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Aquí podrías mostrar un preview de la imagen
                console.log('Image preview:', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
        const name = form.name.value.trim();
        const price = parseFloat(form.price.value);
        const stock = parseInt(form.stock.value);
        
        if (name.length < 3) {
            showAlert('error', 'El nombre del producto debe tener al menos 3 caracteres');
            e.preventDefault();
            return;
        }
        
        if (price <= 0) {
            showAlert('error', 'El precio debe ser mayor a 0');
            e.preventDefault();
            return;
        }
        
        if (stock < 0) {
            showAlert('error', 'El stock no puede ser negativo');
            e.preventDefault();
            return;
        }
    });
});
</script>

<?php $this->load->view('layout/footer'); ?>