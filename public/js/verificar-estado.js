// Script para verificar periódicamente el estado del usuario
document.addEventListener('DOMContentLoaded', function() {
    // No ejecutamos el script si no hay usuario autenticado
    if (!document.body.classList.contains('authenticated')) {
        return;
    }

    // Función para verificar el estado del usuario
    function verificarEstadoUsuario() {
        fetch('/verificar-estado')
            .then(response => response.json())
            .then(data => {
                if (!data.activo) {
                    // Si el usuario está inactivo, redirigir al login sin mostrar alerta
                    window.location.href = '/login?cuenta_desactivada=1';
                }
            })
            .catch(error => {
                console.error('Error al verificar estado del usuario:', error);
            });
    }

    // Verificar el estado cada 30 segundos (30000 ms)
    setInterval(verificarEstadoUsuario, 30000);
});
