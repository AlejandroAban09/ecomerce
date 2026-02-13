<!-- Sistema de Alertas Unificado con SweetAlert2 -->
<div class="flash-container" id="flashContainer"></div>

<!-- SweetAlert2 CDN si no está incluido -->
<?php if (!defined('SWEETALERT2_INCLUDED')): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php define('SWEETALERT2_INCLUDED', true); endif; ?>

<script>
// Configuración global de SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    },
    customClass: {
        container: 'flash-container'
    }
});

// Mostrar mensajes flash del servidor
<?php if ($this->session->flashdata('success')): ?>
    Toast.fire({
        icon: 'success',
        title: '<?= htmlspecialchars($this->session->flashdata('success')); ?>',
        background: '#d4edda',
        color: '#155724'
    });
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    Toast.fire({
        icon: 'error',
        title: '<?= htmlspecialchars($this->session->flashdata('error')); ?>',
        background: '#f8d7da',
        color: '#721c24'
    });
<?php endif; ?>

<?php if ($this->session->flashdata('warning')): ?>
    Toast.fire({
        icon: 'warning',
        title: '<?= htmlspecialchars($this->session->flashdata('warning')); ?>',
        background: '#fff3cd',
        color: '#856404'
    });
<?php endif; ?>

<?php if ($this->session->flashdata('info')): ?>
    Toast.fire({
        icon: 'info',
        title: '<?= htmlspecialchars($this->session->flashdata('info')); ?>',
        background: '#d1ecf1',
        color: '#0c5460'
    });
<?php endif; ?>

// Funciones globales para mostrar alertas
window.showAlert = function(type, title, message = '') {
    const config = {
        success: {
            icon: 'success',
            background: '#d4edda',
            color: '#155724'
        },
        error: {
            icon: 'error', 
            background: '#f8d7da',
            color: '#721c24'
        },
        warning: {
            icon: 'warning',
            background: '#fff3cd', 
            color: '#856404'
        },
        info: {
            icon: 'info',
            background: '#d1ecf1',
            color: '#0c5460'
        }
    };
    
    const settings = config[type] || config.info;
    
    if (message) {
        Swal.fire({
            icon: settings.icon,
            title: title,
            text: message,
            background: settings.background,
            color: settings.color,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Aceptar'
        });
    } else {
        Toast.fire({
            icon: settings.icon,
            title: title,
            background: settings.background,
            color: settings.color
        });
    }
}

// Función para confirmaciones
window.confirmAction = function(title, text, confirmText = 'Confirmar', onConfirm) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        background: '#343a40',
        color: '#fff',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar',
        iconColor: '#ffc107'
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        }
    });
}

// Función para confirmar eliminación
window.confirmDelete = function(url, itemName = '') {
    const text = itemName ? 
        `¿Estás seguro de eliminar "${itemName}"? Esta acción no se puede deshacer.` : 
        '¿Estás seguro de eliminar este elemento? Esta acción no se puede deshacer.';
    
    confirmAction(
        '¿Estás seguro?',
        text,
        'Sí, eliminar',
        function() {
            window.location.href = url;
        }
    );
}

// Función para mostrar notificaciones de formulario
window.showFormErrors = function(errors) {
    let errorList = '';
    if (Array.isArray(errors)) {
        errors.forEach(error => {
            errorList += `• ${error}<br>`;
        });
    } else if (typeof errors === 'object') {
        Object.values(errors).forEach(error => {
            errorList += `• ${error}<br>`;
        });
    } else {
        errorList = `• ${errors}`;
    }
    
    showAlert('error', 'Por favor corrige los siguientes errores:', errorList);
}

// Inicialización de validaciones de formulario
document.addEventListener('DOMContentLoaded', function() {
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            if (alert.style.display !== 'none') {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});
</script>