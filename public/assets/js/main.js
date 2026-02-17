/**
 * Manejo de eventos globales y AJAX para el carrito de compras
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Interceptar envíos de formularios que agregan productos al carrito
    // Buscamos formularios cuya acción contenga 'cart/add'
    const cartForms = document.querySelectorAll('form[action*="cart/add"]');

    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar recarga de página

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;

            // Indicar carga visualmente
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Agregando...';

            const formData = new FormData(this);

            // Realizar petición AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Importante para CodeIgniter is_ajax_request()
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Actualizar contador del carrito en el header
                    const cartCountElement = document.getElementById('cart-count');
                    
                    if (cartCountElement) {
                        // Animación simple al actualizar el número
                        cartCountElement.style.transform = 'scale(1.5)';
                        cartCountElement.innerText = data.cart_count || 0; // fallback a 0 si no viene
                        setTimeout(() => {
                            cartCountElement.style.transform = 'scale(1)';
                        }, 200);
                    }

                    // Mostrar notificación de éxito (Toast)
                    // Usamos la configuración de Toast definida en el footer si está disponible
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    } else if (typeof Swal !== 'undefined') {
                        // Fallback si Toast no está definido globalmente pero Swal sí
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                } else {
                    // Mostrar error
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo agregar el producto.'
                        });
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error en la petición AJAX:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Hubo un problema al comunicarse con el servidor.'
                    });
                }
            })
            .finally(() => {
                // Restaurar estado del botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            });
        });
    });
});
