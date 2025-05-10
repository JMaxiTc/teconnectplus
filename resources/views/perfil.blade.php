@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

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
        <div class="d-flex justify-content-between align-items-center">
            <div class="info-header">Detalles de la Cuenta</div>
            <button class="btn btn-agregar" data-bs-toggle="modal" data-bs-target="#editarModal">
                <i class="bi bi-pencil me-1"></i>Actualizar Datos
            </button>
        </div>

        <div class="info-item">
            <div class="info-label">Nombre Completo</div>
            <div class="info-value">{{ $usuario->nombre }} {{ $usuario->apellido }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Fecha de Nacimiento</div>
            <div class="info-value">{{ $usuario->fechaNacimiento }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Carrera</div>
            <div class="info-value">{{ $usuario->carrera }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Semestre</div>
            <div class="info-value">{{ $usuario->semestre }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Genero</div>
            <div class="info-value">{{ $usuario->genero->genero }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Correo Electrónico</div>
            <div class="info-value">{{ $usuario->correo }}</div>
        </div>
    </div>
</div>

<!-- Modal de edición -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ url('perfil/' . $usuario->id_usuario) .'/actualizar' }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editarModalLabel">Editar Información</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre(s)</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido(s)</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="{{ $usuario->apellido }}">
            </div>
            <div class="mb-3">
                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{ $usuario->fechaNacimiento }}">
            </div>

            <div class="mb-3">
                <label for="carrera" class="form-label">Carrera</label>
                <input type="text" class="form-control" id="carrera" name="carrera" value="{{ $usuario->carrera }}" disabled>
            </div>

            <div class="mb-3">
                <label for="semestre" class="form-label">Semestre</label>
                <input type="number" class="form-control" id="semestre" name="semestre" value="{{ $usuario->semestre }}">
            </div>
            <div class="mb-3">
                <label for="id_genero" class="form-label">Género</label>
                <select class="form-select" id="id_genero" name="id_genero">
                <option value="1" {{ old('id_genero', $usuario->id_genero) == 1 ? 'selected' : '' }}>Masculino</option>
                <option value="2" {{ old('id_genero', $usuario->id_genero) == 2 ? 'selected' : '' }}>Femenino</option>
                <option value="3" {{ old('id_genero', $usuario->id_genero) == 3 ? 'selected' : '' }}>No binario</option>
                <option value="4" {{ old('id_genero', $usuario->id_genero) == 4 ? 'selected' : '' }}>Prefiero no decirlo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" value="{{ $usuario->correo }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Dejar vacío si no desea cambiarla">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Dejar vacío si no desea cambiarla">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-outline-primary rounded-pill px-3">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
