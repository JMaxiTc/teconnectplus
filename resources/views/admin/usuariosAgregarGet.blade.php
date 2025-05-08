@extends("components.layout")
@section('content')
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent
<div class="container">
    <h2 class="mb-4">Crear Nuevo Usuario</h2>

    <form action="{{ url('/admin/usuarios/agregar') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" required>
            @error('apellido')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required>
            @error('fechaNacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="id_genero" class="form-label">Género</label>
            <select class="form-select @error('id_genero') is-invalid @enderror" id="id_genero" name="id_genero" required>
                <option value="">Seleccione una opción</option>
                <option value="1" {{ old('id_genero') == 1 ? 'selected' : '' }}>Masculino</option>
                <option value="2" {{ old('id_genero') == 2 ? 'selected' : '' }}>Femenino</option>
                <option value="3" {{ old('id_genero') == 3 ? 'selected' : '' }}>No binario</option>
                <option value="4" {{ old('id_genero') == 4 ? 'selected' : '' }}>Prefiero no decirlo</option>
            </select>
            @error('id_genero')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-select @error('rol') is-invalid @enderror" id="rol" name="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="ADMIN" {{ old('rol') == 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                <option value="ASESOR" {{ old('rol') == 'ASESOR' ? 'selected' : '' }}>ASESOR</option>
                <option value="ESTUDIANTE" {{ old('rol') == 'ESTUDIANTE' ? 'selected' : '' }}>ESTUDIANTE</option>
            </select>
            @error('rol')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre</label>
            <input type="number" class="form-control @error('semestre') is-invalid @enderror" id="semestre" name="semestre" value="{{ old('semestre') }}" required min="1" max="12">
            @error('semestre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" required>
            @error('correo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success">Crear Usuario</button>
    </form>
</div>
@endsection
