@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-4">

    <!-- Panel de bienvenida -->
    <div class="bg-panel d-flex align-items-center gap-3">
        <i class="bi bi-person-circle fs-2"></i>
        <div>
            <h4 class="mb-0">Actualizar datos de {{ $user->nombre }}!</h4>
            <p class="mb-0">Aquí podras modificar los datos del usuario.</p>
        </div>
    </div>

    <!-- Tarjeta de información -->
    <form action="{{ url('/admin/usuarios/' . $user->id_usuario . '/actualizar') }}" method="POST" class="info-card mt-4">
        @csrf

        <div class="info-header">Detalles de la Cuenta</div>

        <!-- Nombre completo (solo lectura) -->
        <div class="info-item">
            <div class="info-label">Nombre Completo</div>
            <div class="info-value">{{ $user->nombre }} {{ $user->apellido }}</div>
        </div>

        <!-- Fecha de nacimiento (solo lectura) -->
        <div class="info-item">
            <div class="info-label">Fecha de Nacimiento</div>
            <div class="info-value">{{ $user->fechaNacimiento }}</div>
        </div>

        <!-- Semestre (solo lectura) -->
        <div class="info-item">
            <div class="info-label">Semestre</div>
            <div class="info-value">{{ $user->semestre }}</div>
        </div>

        <!-- Género (solo lectura) -->
        <div class="info-item">
            <div class="info-label">Género</div>
            <div class="info-value">{{ $user->genero->genero }}</div>
        </div>

        <!-- Rol (solo lectura) -->
        <div class="info-item">
            <div class="info-label">Rol</div>
            <div class="info-value">{{ $user->rol }}</div>
        </div>

        <!-- Estado (editable) -->
        <div class="info-item">
            <div class="info-label">Estado</div>
            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                <option value="activo" {{ strtolower($user->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ strtolower($user->estado) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estado')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Correo (editable) -->
        <div class="info-item">
            <div class="info-label">Correo</div>
            <input type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo', $user->correo) }}">
            @error('correo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nueva contraseña (editable) -->
        <div class="info-item">
            <div class="info-label">Nueva Contraseña (opcional)</div>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmar nueva contraseña (editable) -->
        <div class="info-item">
            <div class="info-label">Confirmar Nueva Contraseña</div>
            <input type="password" class="form-control" name="password_confirmation">
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Actualizar Usuario</button>
        </div>
    </form>
</div>
@endsection
