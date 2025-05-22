<li class="nav-item me-2">
    <div class="dropdown notification-menu">
        <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell-fill fs-5"></i>
            <span id="notification-badge" class="badge rounded-pill bg-danger notification-badge" style="display: none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0 notification-dropdown shadow" aria-labelledby="notificationsDropdown">
            <div class="notification-header d-flex justify-content-between align-items-center p-2 border-bottom bg-light">
                <h6 class="mb-0 fw-bold">Notificaciones</h6>
                <div>
                    <button class="btn btn-sm btn-link text-decoration-none p-0" id="mark-all-read">
                        <small>Marcar todas leídas</small>
                    </button>
                </div>
            </div>
            <div id="notifications-container" class="notification-body" style="max-height: 350px; overflow-y: auto;">
                <div class="text-center p-3 text-muted">
                    <div class="my-2">
                        <i class="bi bi-bell-slash" style="font-size: 1.5rem;"></i>
                    </div>
                    <p class="mb-0">No tienes notificaciones</p>
                </div>
            </div>
            <div class="notification-footer text-center p-2 border-top">
                <a href="#" class="text-decoration-none small fw-medium">Ver todas las notificaciones</a>
            </div>
        </div>
    </div>
</li>