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
     */    inicializar() {
        // Cargar notificaciones al iniciar
        this.cargarNotificaciones();
        
        // Actualizar más frecuentemente (cada 10 segundos) para notificaciones en tiempo real
        setInterval(() => this.cargarNotificaciones(), 10000);
        
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
     */    cargarNotificaciones() {
        fetch('/notificaciones/recientes')
            .then(response => response.json())
            .then(data => {
                // Verificar si hay nuevas notificaciones
                const nuevasNotificaciones = this.verificarNuevasNotificaciones(data);
                
                // Actualizar la lista de notificaciones
                this.notificaciones = data;
                this.actualizarContador();
                this.renderizarNotificaciones();
                
                // Mostrar toasts para nuevas notificaciones
                if (nuevasNotificaciones.length > 0) {
                    nuevasNotificaciones.forEach(notificacion => {
                        this.mostrarToastNotificacion(notificacion);
                    });
                }
            })
            .catch(error => console.error('Error al cargar notificaciones:', error));
    }
    
    /**
     * Verificar si hay nuevas notificaciones comparando con el estado anterior
     */
    verificarNuevasNotificaciones(nuevasNotificaciones) {
        if (!this.notificaciones.length) return [];
        
        // Encontrar notificaciones que no estaban en la lista anterior
        const notificacionesIds = this.notificaciones.map(n => n.id_notificacion);
        return nuevasNotificaciones.filter(n => !notificacionesIds.includes(n.id_notificacion));
    }
    
    /**
     * Mostrar una notificación toast
     */
    mostrarToastNotificacion(notificacion) {
        // Crear el elemento toast
        const toastEl = document.createElement('div');
        toastEl.className = 'toast show';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
          // Determinar el color e icono según el tipo o estado de la asesoría
        let bgColor = 'bg-info';
        let iconClass = 'bi-info-circle-fill';
        
        // Mapeo de íconos específicos según el tipo de notificación de asesoría
        if (notificacion.titulo.includes('finalizada')) {
            iconClass = 'bi-check-circle-fill';
            bgColor = 'bg-info'; // Color cyan como en la imagen
        } else if (notificacion.titulo.includes('iniciada') || notificacion.titulo.includes('en curso')) {
            iconClass = 'bi-play-circle-fill';
            bgColor = 'bg-info'; // Color cyan
        } else {
            switch (notificacion.tipo) {
                case 'success':
                    bgColor = 'bg-success';
                    iconClass = 'bi-check-circle-fill';
                    break;
                case 'error':
                    bgColor = 'bg-danger';
                    iconClass = 'bi-x-circle-fill';
                    break;
                case 'warning':
                    bgColor = 'bg-warning';
                    iconClass = 'bi-exclamation-circle-fill';
                    break;
            }
        }
        
        // Usar icono personalizado si existe
        if (notificacion.icono) {
            // Convertir íconos de Font Awesome a Bootstrap Icons
            if (notificacion.icono === 'fa-check-circle') {
                iconClass = 'bi-check-circle-fill';
            } else if (notificacion.icono === 'fa-times-circle') {
                iconClass = 'bi-x-circle-fill';
            } else if (notificacion.icono === 'fa-play-circle') {
                iconClass = 'bi-play-circle-fill';
            } else if (notificacion.icono === 'fa-check-double') {
                iconClass = 'bi-check2-all';
            } else if (notificacion.icono === 'fa-calendar-plus') {
                iconClass = 'bi-calendar-plus-fill';
            } else {
                iconClass = 'bi-' + notificacion.icono.replace('fa-', '');
            }
        }
          // Construir el HTML del toast
        toastEl.innerHTML = `
            <div class="toast-header bg-white">
                <div class="${bgColor} rounded-circle p-2 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <i class="bi ${iconClass} text-white"></i>
                </div>
                <strong class="me-auto">${notificacion.titulo}</strong>
                <small class="text-muted">${this.formatearFecha(notificacion.created_at)}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${notificacion.mensaje}
            </div>
        `;
        
        // Agregar el toast al contenedor
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            // Crear el contenedor si no existe
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1080';
            document.body.appendChild(container);
            container.appendChild(toastEl);
        } else {
            toastContainer.appendChild(toastEl);
        }
        
        // Configurar temporizador para eliminar el toast
        setTimeout(() => {
            toastEl.classList.remove('show');
            setTimeout(() => toastEl.remove(), 500);
        }, 5000);
    }
    
    /**
     * Formatear fecha para mostrar en notificaciones
     */
    formatearFecha(fechaStr) {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
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
              // Determinar icono y color según el tipo o estado de la asesoría
            let icon = 'bi-info-circle';
            let bgColor = 'bg-info';
            let iconColor = 'text-white';
            
            // Mapeo de íconos específicos según el tipo de notificación de asesoría
            if (notificacion.titulo.includes('finalizada')) {
                icon = 'bi-check-circle';
                bgColor = 'bg-info'; // Color cyan como en la imagen
                iconColor = 'text-white';
            } else if (notificacion.titulo.includes('iniciada') || notificacion.titulo.includes('en curso')) {
                icon = 'bi-play-circle';
                bgColor = 'bg-info'; // Color cyan
                iconColor = 'text-white';
            } else {
                // Iconos por defecto según tipo
                switch (notificacion.tipo) {
                    case 'success':
                        icon = 'bi-check-circle';
                        bgColor = 'bg-success';
                        break;
                    case 'warning':
                        icon = 'bi-exclamation-triangle';
                        bgColor = 'bg-warning';
                        break;
                    case 'error':
                        icon = 'bi-x-circle';
                        bgColor = 'bg-danger';
                        break;
                    case 'info':
                    default:
                        icon = 'bi-info-circle';
                        bgColor = 'bg-info';
                }
            }
            
            // Usar icono personalizado si existe
            if (notificacion.icono) {
                // Convertir íconos de Font Awesome a Bootstrap Icons
                if (notificacion.icono === 'fa-check-circle') {
                    icon = 'bi-check-circle-fill';
                } else if (notificacion.icono === 'fa-times-circle') {
                    icon = 'bi-x-circle-fill';
                } else if (notificacion.icono === 'fa-play-circle') {
                    icon = 'bi-play-circle-fill';
                } else if (notificacion.icono === 'fa-check-double') {
                    icon = 'bi-check2-all';
                } else if (notificacion.icono === 'fa-calendar-plus') {
                    icon = 'bi-calendar-plus-fill';
                } else {
                    icon = notificacion.icono.replace('fa-', 'bi-');
                }
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
                        <div class="${bgColor} rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                            <i class="bi ${icon} ${iconColor}" style="font-size: 1.2rem;"></i>
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
