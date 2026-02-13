<?php $this->load->view('layout/header', ['title' => 'Registro - Emetix']); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-2">Crear Cuenta</h2>
                        <p class="text-muted">Únete a nosotros hoy mismo</p>
                    </div>

                    <!-- Mensajes Flash -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <?= form_open('auth/register'); ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= set_value('username'); ?>" required>
                        <small class="text-danger"><?= form_error('username'); ?></small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email'); ?>" required>
                        <small class="text-danger"><?= form_error('email'); ?></small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-danger"><?= form_error('password'); ?></small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="passconf" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="passconf" name="passconf" required>
                            <small class="text-danger"><?= form_error('passconf'); ?></small>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-custom btn-lg">Registrarse</button>
                    </div>
                    <?= form_close(); ?>

                    <div class="text-center mt-4">
                        <p class="mb-0">¿Ya tienes cuenta? <a href="<?= base_url('auth/login'); ?>" class="text-primary fw-bold text-decoration-none">Inicia Sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>