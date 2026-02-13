<?php $this->load->view('layout/header', ['title' => 'Iniciar Sesión - Emetix']); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-2">Bienvenido de nuevo</h2>
                        <p class="text-muted">Ingresa tus credenciales para continuar</p>
                    </div>

                    <!-- Mensajes Flash -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>

                    <?= form_open('auth/login'); ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email'); ?>" required autofocus>
                        <small class="text-danger"><?= form_error('email'); ?></small>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-danger"><?= form_error('password'); ?></small>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-custom btn-lg">Iniciar Sesión</button>
                    </div>
                    <?= form_close(); ?>

                    <div class="text-center mt-4">
                        <p class="mb-0">¿No tienes cuenta? <a href="<?= base_url('auth/register'); ?>" class="text-primary fw-bold text-decoration-none">Regístrate aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>