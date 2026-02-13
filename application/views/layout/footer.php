<!-- Footer -->
<footer class="bg-dark-custom text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h5 class="text-primary mb-3">Emetix</h5>
                <p class="text-secondary small">Tu tienda de confianza para la mejor tecnología. Calidad garantizada y envío rápido a todo el país.</p>
                <div class="mt-3">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="col-md-2 mb-4">
                <h6 class="text-uppercase mb-3 fw-bold">Comprar</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Nuevos</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Ofertas</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Categorías</a></li>
                </ul>
            </div>

            <div class="col-md-2 mb-4">
                <h6 class="text-uppercase mb-3 fw-bold">Soporte</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Contacto</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Preguntas Frecuentes</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Devoluciones</a></li>
                </ul>
            </div>

            <div class="col-md-5 mb-4">
                <h6 class="text-uppercase mb-3 fw-bold">Suscríbete</h6>
                <p class="small text-secondary">Recibe las últimas ofertas y novedades.</p>
                <form class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Tu correo electrónico">
                    <button class="btn btn-primary-custom" type="submit">Enviar</button>
                </form>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small text-secondary mb-0">&copy; <?= date('Y') ?> Emetix. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <i class="fab fa-cc-visa text-secondary fs-4 me-2"></i>
                <i class="fab fa-cc-mastercard text-secondary fs-4 me-2"></i>
                <i class="fab fa-cc-paypal text-secondary fs-4"></i>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Configuración base de SweetAlert para Toasts
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        background: '#fff',
        color: '#333',
        iconColor: '#ffc107', // Amarillo primario
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    <?php if ($this->session->flashdata('success')): ?>
        Toast.fire({
            icon: 'success',
            title: '<?= $this->session->flashdata('success'); ?>'
        });
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        Toast.fire({
            icon: 'error',
            title: '<?= $this->session->flashdata('error'); ?>'
        });
    <?php endif; ?>
</script>
<!-- Main JS -->
<script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>

</html>