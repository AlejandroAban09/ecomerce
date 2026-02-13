<?php
// Configuración predeterminada
if (!isset($title)) $title = 'Panel de Usuario - Emetix';
if (!isset($current_page)) $current_page = '';

// Cargar Header
$this->load->view('layout/header', ['title' => $title]);
?>

<div class="main-content-with-sidebar">
    <!-- Sidebar Unificado -->
    <?php $this->load->view('components/sidebar_profile', ['user' => $user, 'current_page' => $current_page]); ?>

    <!-- Main Content Wrapper -->
    <main class="px-md-4 py-4 content-wrapper">
        <!-- Breadcrumb Navigation -->
        <?php $this->load->view('components/breadcrumb', ['current_page' => $current_page]); ?>

        <!-- Sistema de Alertas -->
        <?php $this->load->view('components/flash_messages'); ?>

        <!-- Contenido Específico de la Vista -->
        <?php if (isset($content_view) && $content_view): ?>
            <?php $this->load->view($content_view); ?>
        <?php else: ?>
            <div class="alert alert-warning">No se ha definido una vista de contenido.</div>
        <?php endif; ?>
    </main>
</div>

<?php $this->load->view('layout/footer'); ?>