<!-- Sidebar Unificado para Perfil y Vendedor -->
<aside class="sidebar-profile shadow-lg" id="sidebar">
    <!-- Header del Sidebar con Toggle -->
    <div class="sidebar-header d-flex align-items-center justify-content-between p-3">
        <span class="fw-bold text-white sidebar-brand fade-in d-none d-md-block">PANEL</span>
        <button class="btn btn-sm btn-outline-light border-0" id="sidebarToggle" onclick="toggleSidebarDesktop()">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Toggle móvil (visible solo en móvil) -->
        <button class="sidebar-toggle d-md-none border-0 bg-transparent text-white" onclick="toggleSidebarMobile()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Sección de usuario -->
    <div class="sidebar-user d-flex flex-column align-items-center py-4 transition-all">
        <?php if (isset($user->avatar) && $user->avatar): ?>
            <img src="<?= $user->avatar ?>" alt="<?= $user->username ?>" class="avatar avatar-lg mb-3 shadow-sm">
        <?php else: ?>
            <div class="avatar avatar-lg bg-primary d-flex align-items-center justify-content-center mb-3 shadow-sm rounded-circle">
                <span class="text-white fs-2 fw-bold"><?= strtoupper(substr($user->username, 0, 1)); ?></span>
            </div>
        <?php endif; ?>

        <div class="sidebar-user-text text-center">
            <h6 class="user-name fw-bold mb-1 text-white"><?= htmlspecialchars($user->username); ?></h6>
            <?php if ($user->is_seller): ?>
                <span class="badge bg-warning text-dark rounded-pill px-3">
                    <i class="fas fa-store me-1"></i>Vendedor
                </span>
            <?php else: ?>
                <span class="badge bg-secondary rounded-pill px-3">
                    <i class="fas fa-user me-1"></i>Cliente
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navegación principal -->
    <div class="sidebar-nav">
        <ul class="nav flex-column gap-1">
            <!-- Enlaces de perfil -->
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'account' ? 'active' : '' ?>"
                    href="<?= base_url('profile#account'); ?>"
                    onclick="switchTab('account')"
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Mi Cuenta">
                    <i class="fas fa-user-circle"></i>
                    <span class="nav-text">Mi Cuenta</span>
                </a>
            </li>
<!-- 
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'orders' ? 'active' : '' ?>"
                    href="<?= base_url('profile#orders'); ?>"
                    onclick="switchTab('orders')"
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Mis Pedidos">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="nav-text">Mis Pedidos</span>
                    <?php if (isset($pending_orders_count) && $pending_orders_count > 0): ?>
                        <span class="nav-badge"><?= $pending_orders_count ?></span>
                    <?php endif; ?>
                </a>
            </li> -->

            <!-- <li class="nav-item">
                <a class="nav-link disabled opacity-50" href="#"
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Direcciones (Pronto)">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="nav-text">Direcciones</span>
                    <span class="badge bg-dark fw-normal border border-secondary text-[0.6rem] nav-badge">Pronto</span>
                </a>
            </li> -->

            <!-- Separador -->
            <?php if ($user->is_seller): ?>
                <li class="nav-header mt-4 mb-2 ps-3 text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                    Panel Vendedor
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'seller_dashboard' ? 'active' : '' ?>"
                        href="<?= base_url('seller'); ?>"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Mis Productos">
                        <i class="fas fa-box"></i>
                        <span class="nav-text">Mis Productos</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'sales' ? 'active' : '' ?>"
                        href="<?= base_url('seller/sales'); ?>"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Mis Ventas">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-text">Mis Ventas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'create_product' ? 'active' : '' ?>"
                        href="<?= base_url('seller/create_product'); ?>"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Nuevo Producto">
                        <i class="fas fa-plus-circle"></i>
                        <span class="nav-text">Nuevo Producto</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-header mt-4 mb-2 ps-3 text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                    Oportunidades
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showCreateStoreModal()"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="¡Quiero Vender!">
                        <i class="fas fa-store"></i>
                        <span class="nav-text">¡Quiero Vender!</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Separador y Logout -->
            <li class="nav-header mt-4 mb-2 ps-3 text-uppercase small text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                Sistema
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url(); ?>"
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Inicio">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Inicio</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Overlay para móvil -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content-with-sidebar');

    // Función para alternar sidebar en móvil
    function toggleSidebarMobile() {
        sidebar.classList.toggle('mobile-active');
        overlay.classList.toggle('active');
    }

    // Función para alternar sidebar en desktop (colapsar)
    function toggleSidebarDesktop() {
        if (window.innerWidth >= 768) {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');

            // Guardar preferencia
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar_state', isCollapsed ? 'collapsed' : 'expanded');
        } else {
            toggleSidebarMobile();
        }
    }

    // Inicializar estado guardado y tooltips
    document.addEventListener('DOMContentLoaded', () => {
        // Inicializar Tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        if (window.innerWidth >= 768) {
            const savedState = localStorage.getItem('sidebar_state');
            if (savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
                if (mainContent) mainContent.classList.add('collapsed');
            }
        }
    });

    // Cerrar al hacer clic fuera (móvil)
    if (overlay) {
        overlay.addEventListener('click', toggleSidebarMobile);
    }

    function switchTab(tabName) {
        if (document.querySelector('.nav-pills')) {
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabName + '-tab').classList.add('active');
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            document.getElementById(tabName).classList.add('show', 'active');
            history.pushState(null, null, '#' + tabName);
        }
    }

    function showCreateStoreModal() {
        const modal = new bootstrap.Modal(document.getElementById('createStoreModal'));
        modal.show();
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('mobile-active');
            if (overlay) overlay.classList.remove('active');
        }
    });
</script>