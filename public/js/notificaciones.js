/**
 * Sistema de notificaciones para TeConnect+
 */
class NotificationManager {
    constructor() {
        // Elementos del DOM
        this.badge = document.getElementById('notification-badge');
        this.container = document.getElementById('notifications-container');
        this.markAllReadBtn = document.getElementById('mark-all-read');
        this.dropdown = document.getElementById('notificationsDropdown');
        
        // Estado
        this.notificaciones = [];
        this.noLeidas = 0;
        
        // Inicializar
        this.inicializar();
    }
    
    /**
     * Inicializar el gestor de notificaciones
     */
    inicializar() {
        // Cargar notificaciones al iniciar
        this.cargarNotificaciones();
        
        // Actualizar cada minuto
        setInterval(() => this.cargarNotificaciones(), 60000);
        
        // Configurar eventos
        if (this.markAllReadBtn) {
            this.markAllReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.marcarTodasComoLeidas();
            });
        }
        
        // Si hay dropdown de notificaciones, configurar evento para marcar como leídas al abrir
        if (this.dropdown) {
            this.dropdown.addEventListener('shown.bs.dropdown', () => {
                this.marcarNotificacionesVisiblesComoLeidas();
            });
        }
    }
    
    /**
     * Cargar notificaciones desde el servidor
     */
    cargarNotificaciones() {
        fetch('/notificaciones')
            .then(response => response.json())
            .then(data => {
                this.notificaciones = data;
                this.actualizarContador();
                this.renderizarNotificaciones();
            })
            .catch(error => console.error('Error al cargar notificaciones:', error));
    }
    
    /**
     * Actualizar el contador de notificaciones
     */
    actualizarContador() {
        fetch('/notificaciones/conteo')
            .then(response => response.json())
            .then(data => {
                this.noLeidas = data.count;
                
                if (this.badge) {
                    if (this.noLeidas > 0) {
                        this.badge.textContent = this.noLeidas > 99 ? '99+' : this.noLeidas;
                        this.badge.style.display = 'block';
                        this.badge.classList.add('pulse');
                        
                        // Quitar animación después de 3 segundos
                        setTimeout(() => {
                            this.badge.classList.remove('pulse');
                        }, 3000);
                    } else {
                        this.badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error al actualizar contador:', error));
    }
    
    /**
     * Renderizar las notificaciones en el contenedor
     */
    renderizarNotificaciones() {
        if (!this.container) return;
        
        if (this.notificaciones.length === 0) {
            this.container.innerHTML = `
                <div class="text-center p-3 text-muted">
                    <div class="my-3">
                        <i class="bi bi-bell-slash" style="font-size: 1.5rem;"></i>
                    </div>
                    <p>No tienes notificaciones</p>
                </div>
            `;
            return;
        }
        
        this.container.innerHTML = '';
        
        this.notificaciones.forEach(notificacion => {
            const item = document.createElement('div');
            item.className = `notification-item position-relative ${notificacion.leida ? '' : 'unread'}`;
            item.dataset.id = notificacion.id;
            
            // Determinar icono según el tipo
            let icon = 'bi-info-circle';
            switch (notificacion.tipo) {
                case 'success':
                    icon = 'bi-check-circle';
                    break;
                case 'warning':
                    icon = 'bi-exclamation-triangle';
                    break;
                case 'error':
                    icon = 'bi-x-circle';
                    break;
                case 'info':
                default:
                    icon = 'bi-info-circle';
            }
            
            // Usar icono personalizado si existe
            if (notificacion.icono) {
                icon = notificacion.icono;
            }
            
            // Formatear fecha
            const fecha = new Date(notificacion.created_at);
            const fechaFormateada = fecha.toLocaleDateString('es-MX', { 
                day: '2-digit', 
                month: 'short', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            item.innerHTML = `
                <div class="d-flex">
                    <div class="me-3">
                        <div class="bg-light rounded-circle p-2">
                            <i class="bi ${icon} text-${notificacion.tipo}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">${notificacion.titulo}</h6>
                        <p class="text-muted small mb-1">${notificacion.mensaje}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${fechaFormateada}</small>
                            <button class="btn btn-sm btn-link text-danger p-0 delete-notification" 
                                    data-id="${notificacion.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            this.container.appendChild(item);
            
            // Agregar evento para marcar como leída al hacer clic
            item.addEventListener('click', (e) => {
                // Si se hizo clic en el botón de eliminar, no hacer nada
                if (e.target.closest('.delete-notification')) {
                    return;
                }
                
                this.marcarComoLeida(notificacion.id);
                
                // Si hay una URL, redirigir
                if (notificacion.url) {
                    window.location.href = notificacion.url;
                }
            });
            
            // Agregar evento para eliminar
            const deleteBtn = item.querySelector('.delete-notification');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.eliminarNotificacion(notificacion.id);
                });
            }
        });
    }
    
    /**
     * Marcar una notificación como leída
     */
    marcarComoLeida(id) {
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/notificaciones/${id}/leer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar localmente
                const index = this.notificaciones.findIndex(n => n.id === id);
                if (index !== -1) {
                    this.notificaciones[index].leida = true;
                    this.renderizarNotificaciones();
                    this.actualizarContador();
                }
            }
        })
        .catch(error => console.error('Error al marcar como leída:', error));
    }
    
    /**
     * Marcar todas las notificaciones como leídas
     */
    marcarTodasComoLeidas() {
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/notificaciones/leer-todas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar localmente
                this.notificaciones.forEach(n => n.leida = true);
                this.renderizarNotificaciones();
                this.actualizarContador();
            }
        })
        .catch(error => console.error('Error al marcar todas como leídas:', error));
    }
    
    /**
     * Marcar como leídas las notificaciones visibles en el dropdown
     */
    marcarNotificacionesVisiblesComoLeidas() {
        // Obtener todas las notificaciones no leídas
        const noLeidas = this.notificaciones.filter(n => !n.leida);
        
        // Si no hay notificaciones no leídas, no hacer nada
        if (noLeidas.length === 0) return;
        
        // Marcar como leídas las primeras 5 notificaciones no leídas (las visibles)
        const visibles = noLeidas.slice(0, 5);
        visibles.forEach(n => this.marcarComoLeida(n.id));
    }
    
    /**
     * Eliminar una notificación
     */
    eliminarNotificacion(id) {
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/notificaciones/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar del arreglo local
                this.notificaciones = this.notificaciones.filter(n => n.id !== id);
                this.renderizarNotificaciones();
                this.actualizarContador();
            }
        })
        .catch(error => console.error('Error al eliminar notificación:', error));
    }
}

// Inicializar el gestor de notificaciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Solo inicializar si el usuario está autenticado
    if (document.body.classList.contains('user-auth')) {
        console.log('Inicializando sistema de notificaciones...');
        window.notificationManager = new NotificationManager();
    }
});
