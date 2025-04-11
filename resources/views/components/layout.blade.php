<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TeConnect+</title>

    <!-- Cargar Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" />
    <link href="{{ URL::asset('DataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/style.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5 pt-4">
        @include('components.menu') <!-- Sidebar a la izquierda -->
        
        <div class="flex-grow-1 main-content p-4">
            @yield('content') <!-- Aquí va el contenido de cada vista -->
        </div>
    </div>

    <!-- Cargar JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('bootstrap-5.3.5-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('DataTables/datatables.min.js') }}"></script>
</body>
</html>
