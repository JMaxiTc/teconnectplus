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
                        <a class="nav-link {{ request()->is('asesorias*') ? 'active' : '' }}" href="/asesorias">Asesorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('materiales*') ? 'active' : '' }}" href="/materiales">Materiales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('materias*') ? 'active' : '' }}" href="{{ url('/catalogos/materias') }}">Materias</a>
                    </li>
                    
                    <!-- Opciones específicas para asesores -->
                    @if(Auth::user()->esAsesor())
                        <li class="nav-item role-asesor-only">
                            <a class="nav-link {{ request()->is('mis-materias*') ? 'active' : '' }}" href="/mis-materias">
                                <i class="fas fa-book-reader me-1"></i>Mis Materias
                            </a>
                        </li>
                        <li class="nav-item role-asesor-only">
                            <a class="nav-link {{ request()->is('solicitudes*') ? 'active' : '' }}" href="/solicitudes">
                                <i class="fas fa-clipboard-list me-1"></i>Solicitudes
                            </a>
                        </li>
                    @endif
                    
                    <!-- Opciones específicas para estudiantes -->
                    @if(Auth::user()->esEstudiante())
                        <li class="nav-item role-estudiante-only">
                            <a class="nav-link {{ request()->is('mis-asesorias*') ? 'active' : '' }}" href="/mis-asesorias">
                                <i class="fas fa-calendar-check me-1"></i>Mis Asesorías
                            </a>
                        </li>
                        <li class="nav-item role-estudiante-only">
                            <a class="nav-link {{ request()->is('buscar-asesor*') ? 'active' : '' }}" href="/buscar-asesor">
                                <i class="fas fa-search me-1"></i>Buscar Asesor
                            </a>
                        </li>
                    @endif
                    
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