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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="
    @guest
        user-guest
    @else
        user-auth role-{{ strtolower(Auth::user()->rol) }}
    @endguest
">
    <div class="container mt-5 pt-4">
        @include('components.menu') <!-- Header -->
        
        <div class="flex-grow-1 main-content p-4">
            @yield('content') <!-- Aquí va el contenido de cada vista -->
        </div>
    </div>

    <!-- Cargar JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Actualización: Cargar Bootstrap Bundle que incluye Popper -->
    <script src="{{ URL::asset('bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('DataTables/datatables.min.js') }}"></script>
    
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
</body>
</html>
