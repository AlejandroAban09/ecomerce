<?php $this->load->view('layout/header', ['title' => 'Editar Producto - Emetix']); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-dark-custom text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Producto</h5>
                    <a href="<?= base_url('seller'); ?>" class="btn btn-sm btn-outline-light">Volver al Panel</a>
                </div>
                <div class="card-body p-4">

                    <!-- Mensajes Flash de Error -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <!-- Formulario Multipart -->
                    <?= form_open_multipart('seller/edit_product/' . $product->id); ?>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $product->name); ?>" required>
                        <small class="text-danger"><?= form_error('name'); ?></small>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-bold">Precio ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= set_value('price', $product->price); ?>" required>
                            </div>
                            <small class="text-danger"><?= form_error('price'); ?></small>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-bold">Stock Disponible</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="<?= set_value('stock', $product->stock); ?>" required>
                            <small class="text-danger"><?= form_error('stock'); ?></small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold">Estado</label>
                            <select class="form-select" name="status" id="status">
                                <option value="active" <?= ($product->status == 'active') ? 'selected' : ''; ?>>Activo</option>
                                <option value="paused" <?= ($product->status == 'paused') ? 'selected' : ''; ?>>Pausado</option>
                                <option value="draft" <?= ($product->status == 'draft') ? 'selected' : ''; ?>>Borrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Descripción Detallada</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= set_value('description', $product->description); ?></textarea>
                        <small class="text-danger"><?= form_error('description'); ?></small>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">Imagen Principal (Opcional)</label>

                        <?php if (!empty($product->image_url)): ?>
                            <div class="mb-2">
                                <img src="<?= $product->image_url; ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
                                <div class="form-text text-muted">Imagen actual</div>
                            </div>
                        <?php endif; ?>

                        <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        <div class="form-text">Sube una nueva imagen solo si deseas reemplazar la actual.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                        <a href="<?= base_url('seller'); ?>" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary-custom px-5">Guardar Cambios</button>
                    </div>

                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>