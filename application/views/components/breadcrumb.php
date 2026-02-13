<!-- Breadcrumb Navigation Global -->
<?php
// Configuración de breadcrumbs por página
$breadcrumb_config = [
    'profile' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => null]
    ],
    'seller_dashboard' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => base_url('profile')],
        ['label' => 'Panel Vendedor', 'url' => null]
    ],
    'sales' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => base_url('profile')],
        ['label' => 'Panel Vendedor', 'url' => base_url('seller')],
        ['label' => 'Mis Ventas', 'url' => null]
    ],
    'create_product' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => base_url('profile')],
        ['label' => 'Panel Vendedor', 'url' => base_url('seller')],
        ['label' => 'Nuevo Producto', 'url' => null]
    ],
    'edit_product' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => base_url('profile')],
        ['label' => 'Panel Vendedor', 'url' => base_url('seller')],
        ['label' => 'Editar Producto', 'url' => null]
    ],
    'order_detail' => [
        ['label' => 'Inicio', 'url' => base_url()],
        ['label' => 'Mi Perfil', 'url' => base_url('profile')],
        ['label' => 'Mis Pedidos', 'url' => base_url('profile#orders')],
        ['label' => 'Detalle del Pedido', 'url' => null]
    ]
];

// Determinar breadcrumb actual
$current_breadcrumb = isset($breadcrumb_config[$current_page]) ? 
                     $breadcrumb_config[$current_page] : 
                     [
                         ['label' => 'Inicio', 'url' => base_url()],
                         ['label' => $page_title ?? 'Página Actual', 'url' => null]
                     ];
?>

<nav aria-label="breadcrumb" class="breadcrumb-nav mb-4 fade-in">
    <div class="container-fluid px-md-4">
        <ol class="breadcrumb bg-transparent mb-0">
            <?php foreach ($current_breadcrumb as $index => $crumb): ?>
                <?php if ($index === count($current_breadcrumb) - 1): ?>
                    <!-- Último elemento (página actual) -->
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-chevron-right me-1 text-muted small"></i>
                        <?= htmlspecialchars($crumb['label']); ?>
                    </li>
                <?php else: ?>
                    <!-- Elementos navegables -->
                    <li class="breadcrumb-item">
                        <?php if ($crumb['url']): ?>
                            <a href="<?= htmlspecialchars($crumb['url']); ?>" class="text-decoration-none">
                                <?php if ($index === 0): ?>
                                    <i class="fas fa-home me-1"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($crumb['label']); ?>
                            </a>
                        <?php else: ?>
                            <span><?= htmlspecialchars($crumb['label']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>

<!-- Estilos adicionales para breadcrumb -->
<style>
.breadcrumb-nav .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-nav .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    font-weight: bold;
    font-size: 1.2rem;
    padding: 0 0.5rem;
}

.breadcrumb-nav .breadcrumb-item a {
    color: #6c757d;
    transition: var(--transition-fast);
}

.breadcrumb-nav .breadcrumb-item a:hover {
    color: var(--primary-color);
}

.breadcrumb-nav .breadcrumb-item.active {
    color: var(--text-color);
    font-weight: var(--font-weight-medium);
}

/* Versión móvil */
@media (max-width: 768px) {
    .breadcrumb-nav {
        margin-bottom: 1rem !important;
    }
    
    .breadcrumb-nav .breadcrumb {
        font-size: var(--font-size-sm);
    }
    
    .breadcrumb-nav .breadcrumb-item {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
}
</style>