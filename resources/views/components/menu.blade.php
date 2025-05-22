<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ URL::asset('assets/logo.png') }}" alt="Logo TeConnect+" height="40">
            TeConnect+
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Inicio</a>
                </li>
                
                @auth
                    <!-- Opciones comunes para usuarios autenticados -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('materiales*') ? 'active' : '' }}" href="/materiales">Materiales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('materias*') ? 'active' : '' }}" href="{{ url('/catalogos/materias') }}">Materias</a>
                    </li>
                    
                    <!-- Opciones específicas para asesores -->
                    @if(Auth::user()->esAsesor())
                        <li class="nav-item role-asesor-only">
                            <a class="nav-link {{ request()->is('mis-materias*') ? 'active' : '' }}" href="/asesor/mis-materias">
                                <i class="fas fa-book-reader me-1"></i>Mis Materias
                            </a>
                        </li>
                        <li class="nav-item role-asesor-only dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('asesoriasa*') ? 'active' : '' }}" href="#" id="asesoriasDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chalkboard-teacher me-1"></i>Asesorías
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="asesoriasDropdown">
                                <li><a class="dropdown-item" href="{{ route('asesoriasa.activas.get') }}"><i class="fas fa-calendar-check me-1"></i>Asesorías Activas <span id="menu-active-count" class="badge bg-success rounded-pill">0</span></a></li>
                                <li><a class="dropdown-item" href="{{ route('asesoriasa.solicitudes.get') }}"><i class="fas fa-clipboard-list me-1"></i>Solicitudes <span id="menu-pending-count" class="badge bg-primary rounded-pill">0</span></a></li>
                                <li><a class="dropdown-item" href="{{ route('asesoriasa.historial.get') }}"><i class="fas fa-history me-1"></i>Historial</a></li>
                                <li><a class="dropdown-item" href="{{ route('asesoriasa.calificaciones') }}"><i class="fas fa-star me-1"></i>Mis Calificaciones</a></li>
                            </ul>
                        </li>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Cargar contador de solicitudes pendientes
                                fetch("{{ route('asesoriasa.solicitudes.count') }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        const badge = document.getElementById('menu-pending-count');
                                        if (badge) {
                                            badge.textContent = data.count;
                                            if (data.count > 0) {
                                                badge.classList.add('pulse');
                                            }
                                        }
                                    });
                                    
                                // Cargar contador de asesorías activas
                                fetch("{{ route('asesoriasa.activas.count') }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        const badge = document.getElementById('menu-active-count');
                                        if (badge) {
                                            badge.textContent = data.count;
                                            if (data.count > 0) {
                                                badge.classList.add('pulse');
                                            }
                                        }
                                    });
                            });
                        </script>
                    @endif
                    
                    <!-- Opciones específicas para estudiantes -->
                    @if(Auth::user()->esEstudiante())
                        <li class="nav-item role-estudiante-only dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('asesorias*') ? 'active' : '' }}" href="#" id="estudianteAsesoriasDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chalkboard-teacher me-1"></i>Mis Asesorías
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="estudianteAsesoriasDropdown">
                                <li><a class="dropdown-item" href="{{ route('asesorias.index') }}"><i class="fas fa-calendar-check me-1"></i>Asesorías Activas <span id="menu-estudiante-active-count" class="badge bg-success rounded-pill">0</span></a></li>
                                <li><a class="dropdown-item" href="{{ route('asesorias.pendientes.get') }}"><i class="fas fa-hourglass-half me-1"></i>Pendientes <span id="menu-estudiante-pending-count" class="badge bg-primary rounded-pill">0</span></a></li>
                                <li><a class="dropdown-item" href="{{ route('asesorias.historial.get') }}"><i class="fas fa-history me-1"></i>Historial</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('asesorias.solicitar.get') }}"><i class="fas fa-plus me-1"></i>Solicitar Asesoría</a></li>
                            </ul>
                        </li>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Cargar contador de solicitudes pendientes para estudiantes
                                fetch("{{ route('asesorias.pendientes.count') }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        const badge = document.getElementById('menu-estudiante-pending-count');
                                        if (badge) {
                                            badge.textContent = data.count;
                                            if (data.count > 0) {
                                                badge.classList.add('pulse');
                                            }
                                        }
                                    });
                                    
                                // Cargar contador de asesorías activas para estudiantes
                                fetch("{{ route('asesorias.activas.count') }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        const badge = document.getElementById('menu-estudiante-active-count');
                                        if (badge) {
                                            badge.textContent = data.count;
                                            if (data.count > 0) {
                                                badge.classList.add('pulse');
                                            }
                                        }
                                    });
                            });
                        </script>
                    @endif

                                        <!-- Iconos de navegación extra (notificaciones y perfil) -->
                    <li class="nav-item">
                        <a class="nav-link position-relative mx-1" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span id="notification-badge" class="badge rounded-pill bg-danger notification-badge" style="display: none;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-0 notification-dropdown shadow" aria-labelledby="notificationsDropdown">
                            <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
                                <h6 class="mb-0 fw-bold">Notificaciones</h6>
                                <div>
                                    <button class="btn btn-sm btn-link text-decoration-none" id="mark-all-read">Marcar todas como leídas</button>
                                </div>
                            </div>
                            <div id="notifications-container" class="notification-body" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-center p-3 text-muted">
                                    <div class="my-3">
                                        <i class="bi bi-bell-slash" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <p>No tienes notificaciones</p>
                                </div>
                            </div>
                            <div class="notification-footer text-center p-2 border-top">
                                <a href="#" class="text-decoration-none small">Ver todas las notificaciones</a>
                            </div>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="badge bg-{{ Auth::user()->esAsesor() ? 'primary' : 'success' }} me-1">
                                {{ Auth::user()->rol }}
                            </span>
                            {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/perfil">Mi Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Registrarse</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>