@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<style>
    .bg-panel {
        background-color: #198754; /* Verde institucional */
        color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 0.75rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .info-header {
        font-weight: bold;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: #198754;
    }

    .info-item {
        margin-bottom: 1.25rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.05rem;
        color: #212529;
        font-weight: 500;
    }
</style>

<div class="container mt-4">
    

    <!-- Panel de bienvenida -->
    <div class="bg-panel d-flex align-items-center gap-3">
        <i class="bi bi-person-circle fs-2"></i>
        <div>
            <h4 class="mb-0">¡Hola, {{ $usuario->nombre }}!</h4>
            <p class="mb-0">Aquí puedes consultar los datos de tu cuenta.</p>
        </div>
    </div>

    <!-- Tarjeta de información -->
    <div class="info-card">
        <div class="info-header">Detalles de la Cuenta</div>


        <div class="info-item">
            <div class="info-label">Nombre Completo</div>
            <div class="info-value">{{ $usuario->nombre }} {{ $usuario->apellido }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Correo Electrónico</div>
            <div class="info-value">{{ $usuario->correo }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Fecha de Nacimiento</div>
            <div class="info-value">{{ $usuario->fechaNacimiento }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Semestre</div>
            <div class="info-value">{{ $usuario->semestre }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Genero</div>
            <div class="info-value">{{ $usuario->genero->genero }}</div>
        </div>
    </div>
</div>
@endsection
