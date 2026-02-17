<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Dinámico -->
    <title><?= isset($meta_title) ? html_escape($meta_title) : 'Emetix - Ecomerce Moderno' ?></title>
    <meta name="description" content="<?= isset($meta_description) ? html_escape(strip_tags($meta_description)) : 'Encuentra los mejores productos de electrónica al mejor precio en Emetix.' ?>">
    <meta name="keywords" content="<?= isset($meta_keywords) ? html_escape(strip_tags($meta_keywords)) : 'electronica, ecommerce, tecnologia, emetix' ?>">
    <link rel="canonical" href="<?= current_url() ?>">

    <!-- Open Graph (Facebook, LinkedIn, WhatsApp) -->
    <meta property="og:title" content="<?= isset($meta_title) ? html_escape($meta_title) : 'Emetix' ?>">
    <meta property="og:description" content="<?= isset($meta_description) ? html_escape(strip_tags($meta_description)) : 'Tu tienda de confianza.' ?>">
    <meta property="og:image" content="<?= isset($og_image) ? html_escape($og_image) : base_url('assets/img/logo-emetix.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= isset($meta_title) ? html_escape($meta_title) : 'Emetix' ?>">
    <meta name="twitter:description" content="<?= isset($meta_description) ? html_escape(strip_tags($meta_description)) : 'Tu tienda de confianza.' ?>">
    <meta name="twitter:image" content="<?= isset($og_image) ? html_escape($og_image) : base_url('assets/img/logo-emetix.png') ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/estilos.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css?v=' . time()) ?>">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-bolt text-primary me-2"></i>Emetix
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) == '' || $this->uri->segment(1) == 'home') ? 'active' : '' ?>" href="<?= base_url() ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) == 'shop') ? 'active' : '' ?>" href="<?= base_url('shop') ?>">Tienda</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) == 'blog') ? 'active' : '' ?>" href="<?= base_url('blog') ?>">Blog</a></li>
                    <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) == 'contact') ? 'active' : '' ?>" href="<?= base_url('contact') ?>">Contacto</a></li>
                </ul>

                <div class="d-flex align-items-center">
                    <form action="<?= base_url('shop') ?>" method="get" class="d-flex align-items-center me-3">
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control border-end-0" placeholder="Buscar..." aria-label="Buscar">
                            <button class="btn btn-outline-secondary border-start-0" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>

                    <?php if ($this->session->userdata('logged_in')): ?>
                        <div class="dropdown ms-3">
                            <a href="#" class="nav-icon dropdown-toggle text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="far fa-user text-primary"></i>
                                <span class="fs-6 d-none d-md-inline text-dark ms-1"><?= $this->session->userdata('username'); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-id-card me-2 text-muted"></i> Mi Perfil</a></li>
                                <?php if ($this->session->userdata('role') === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('admin') ?>"><i class="fas fa-cogs me-2 text-muted"></i> Panel Admin</a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('auth/login') ?>" class="nav-icon d-flex align-items-center text-decoration-none" title="Iniciar Sesión">
                            <i class="far fa-user"></i>
                            <span class="fs-6 d-none d-md-inline ms-2" style="font-size: 0.9rem; font-weight: 500;">Acceder / Registro</span>
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('cart') ?>" class="nav-icon position-relative ms-3">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary text-dark" style="font-size: 0.6rem;"><?= $this->cart->total_items(); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>