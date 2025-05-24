<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TeConnect+</title>

    <!-- Cargar Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" />
    <link href="{{ URL::asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/style.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/calificaciones.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @yield('styles')
    
    <style>
        /* Estilos para el sistema de notificaciones */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            animation-duration: 0.5s;
        }
        
        .notification-badge.pulse {
            animation-name: notification-pulse;
            animation-iteration-count: 3;
        }
        
        @keyframes notification-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .notification-dropdown {
            width: 320px;
            max-width: 90vw;
            border-radius: 0.5rem;
        }
        
        .notification-item {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .notification-item:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .notification-item.unread {
            background-color: rgba(13, 110, 253, 0.08);
            border-left: 3px solid #0d6efd;
        }
        
        .notification-item.unread:hover {
            background-color: rgba(13, 110, 253, 0.15);
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item .icon-wrapper {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        /* Animación para nuevas notificaciones */
        @keyframes highlight-new {
            from { background-color: rgba(13, 110, 253, 0.2); }
            to { background-color: rgba(13, 110, 253, 0.08); }
        }
        
        .notification-item.new-notification {
            animation: highlight-new 2s ease;
        }
    </style>
</head>
<body class="
    @guest
        user-guest
    @else
        user-auth role-{{ strtolower(Auth::user()->rol) }} authenticated
    @endguest
">
    <div class="container mt-5 pt-4">
        @include('components.menu') <!-- Header -->
        
        <div class="flex-grow-1 main-content p-4">
            @yield('content') <!-- Aquí va el contenido de cada vista -->
            @include('components.notificacion-toast')
        </div>
    </div>
    
    <!-- Contenedor para toasts de notificaciones en tiempo real -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;"></div>

    <!-- Cargar JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Actualización: Cargar Bootstrap Bundle que incluye Popper -->
    <script src="{{ URL::asset('bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('DataTables/datatables.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('js/interceptor.js') }}"></script>
    <script src="{{ URL::asset('js/notificaciones.js') }}"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    <script src="{{ URL::asset('js/verificar-estado.js') }}"></script>
    
    <!-- Scripts para control de roles -->
    <script>
        // Variable global para identificar el rol del usuario
        let userRole = '{{ Auth::check() ? Auth::user()->rol : "GUEST" }}';
        
        // Función para verificar si el usuario tiene un rol específico
        function hasRole(role) {
            return userRole === role;
        }
        
        // Función para mostrar/ocultar elementos según el rol
        $(document).ready(function() {
            // Elementos visibles solo para asesores
            $('.role-asesor-only').each(function() {
                $(this).toggleClass('d-none', !hasRole('ASESOR'));
            });
            
            // Elementos visibles solo para estudiantes
            $('.role-estudiante-only').each(function() {
                $(this).toggleClass('d-none', !hasRole('ESTUDIANTE'));
            });
            
            // Elementos visibles solo para usuarios autenticados
            $('.role-auth-only').each(function() {
                $(this).toggleClass('d-none', userRole === 'GUEST');
            });
            
            // Inicializar los dropdowns de Bootstrap manualmente
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
