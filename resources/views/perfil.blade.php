@extends('components.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/perfil.css') }}">
<link rel="stylesheet" href="{{ asset('assets/perfil-layout.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/toggle-estado.js') }}"></script>
@endsection

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container my-4">
    
    <!-- Panel de bienvenida con estilo header -->
    <div class="mb-3 rounded-3 text-white p-3" style="background: linear-gradient(135deg, #1a5276, #2980b9, #3498db); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-white p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="fas fa-user-circle text-primary fs-3"></i>
            </div>
            <div>
                <h2 class="mb-0 fs-4">Mi Perfil</h2>
                <p class="mb-0 small">¡Hola, {{ $usuario->nombre }}! Aquí puedes consultar y actualizar tus datos personales.</p>
            </div>
        </div>
    </div>

    <!-- Tarjeta de información con edición en línea -->
    <div class="card solicitud-card">
        <div class="card-header text-white py-2" style="background: linear-gradient(135deg, #198754, #157347);">
            <div class="d-flex align-items-center">
                <div class="me-2 d-flex align-items-center justify-content-center rounded-circle bg-white text-success" style="width: 32px; height: 32px;">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 class="card-title mb-0">
                    Detalles de la Cuenta
                </h3>
            </div>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('perfil.update', ['id_usuario' => $usuario->id_usuario]) }}" method="POST" id="perfilForm">
                @csrf
                <div class="row row-cols-1 row-cols-md-2 g-2">
                    <!-- Nombre completo -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Nombre Completo</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-nombre">
                                    <p class="info-text mb-0 fw-bold">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="nombre">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-nombre">
                                    <div class="row gx-2">
                                        <div class="col">
                                            <input type="text" class="form-control form-control-sm mb-1" id="nombre" name="nombre" value="{{ $usuario->nombre }}" placeholder="Nombre">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control form-control-sm mb-1" id="apellido" name="apellido" value="{{ $usuario->apellido }}" placeholder="Apellido">
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success guardar-campo" data-campo="nombre">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="nombre">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fecha de nacimiento -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Fecha de Nacimiento</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-fecha">
                                    <p class="info-text mb-0 fw-bold">{{ $usuario->fechaNacimiento }}</p>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="fecha">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-fecha">
                                    <div class="row gx-2">
                                        <div class="col">
                                            <input type="date" class="form-control form-control-sm mb-1" id="fechaNacimiento" name="fechaNacimiento" value="{{ $usuario->fechaNacimiento }}">
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success guardar-campo" data-campo="fecha">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="fecha">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Carrera -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="info-content w-100">
                                <span class="info-label small text-muted d-block">Carrera</span>
                                <p class="info-text mb-0 fw-bold">{{ $usuario->carrera }}</p>
                                <small class="text-muted small">La carrera no se puede modificar</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Semestre -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Semestre</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-semestre">
                                    <div class="d-flex align-items-center">
                                        <div class="semester-circle d-flex align-items-center justify-content-center text-white rounded-circle" 
                                             style="width: 35px; height: 35px; font-weight: bold; font-size: 1rem; background-color: #198754 !important;">
                                            {{ $usuario->semestre }}
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="semestre">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-semestre">
                                    <div class="row gx-2">
                                        <div class="col-md-4">
                                            <input type="number" class="form-control form-control-sm mb-1" id="semestre" name="semestre" value="{{ $usuario->semestre }}" min="1" max="12">
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success guardar-campo" data-campo="semestre">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="semestre">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Género -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Género</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-genero">
                                    <p class="info-text mb-0 fw-bold">{{ $usuario->genero->genero }}</p>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="genero">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-genero">
                                    <div class="row gx-2">
                                        <div class="col-md-8">
                                            <select class="form-select form-select-sm mb-1" id="id_genero" name="id_genero">
                                                <option value="1" {{ old('id_genero', $usuario->id_genero) == 1 ? 'selected' : '' }}>Masculino</option>
                                                <option value="2" {{ old('id_genero', $usuario->id_genero) == 2 ? 'selected' : '' }}>Femenino</option>
                                                <option value="3" {{ old('id_genero', $usuario->id_genero) == 3 ? 'selected' : '' }}>No binario</option>
                                                <option value="4" {{ old('id_genero', $usuario->id_genero) == 4 ? 'selected' : '' }}>Prefiero no decirlo</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success guardar-campo" data-campo="genero">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="genero">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Correo electrónico -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Correo Electrónico</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-correo">
                                    <p class="info-text mb-0 fw-bold">{{ $usuario->correo }}</p>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="correo">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-correo">
                                    <div class="row gx-2">
                                        <div class="col">
                                            <input type="email" class="form-control form-control-sm mb-1" id="correo" name="correo" value="{{ $usuario->correo }}">
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-success guardar-campo" data-campo="correo">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="correo">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contraseña -->
                    <div class="col mb-2">
                        <div class="info-item1 d-flex align-items-center border rounded p-3 bg-light h-100 shadow-sm">
                            <div class="info-icon me-3 text-primary">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="info-content position-relative w-100">
                                <span class="info-label small text-muted d-block">Contraseña</span>
                                
                                <!-- Vista normal -->
                                <div class="d-flex align-items-center justify-content-between campo-vista" id="vista-password">
                                    <p class="info-text mb-0 fw-bold">••••••••</p>
                                    <button type="button" class="btn btn-sm ms-3 p-0 text-primary editar-campo" data-campo="password">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                
                                <!-- Formulario de edición -->
                                <div class="campo-edicion d-none" id="edicion-password">
                                    <div class="row gx-2">
                                        <div class="col-md-6 mb-1">
                                            <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Nueva contraseña">
                                            <div id="password-error" class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirmar">
                                            <div id="password-confirm-error" class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="text-muted small">Mín. 8 caracteres alfanuméricos</small>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-success guardar-campo" data-campo="password">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary cancelar-edicion" data-campo="password">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@if (auth()->user()->rol === 'ASESOR')
<div class="card mt-5 shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Horario de Disponibilidad</h5>
        <div class="form-check form-switch text-white">
            <input class="form-check-input" type="checkbox" id="toggleInactivos">
            <label class="form-check-label small" for="toggleInactivos">
                <i class="fas fa-filter"></i> Mostrar horarios inactivos
            </label>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <div>Completa tu horario de disponibilidad para que los estudiantes puedan agendar asesorías contigo. Los horarios <strong>activos</strong> serán visibles para los estudiantes.</div>
        </div>

        <h6 class="text-primary"><i class="fas fa-table me-1"></i>Mis horarios disponibles</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Día</th>
                        <th>Hora inicio</th>
                        <th>Hora fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaHorarios">
                    @foreach ($usuario->disponibilidades->where('id_asesor', Auth::id()) as $disp)
                    <tr class="{{ $disp->estado === 'INACTIVO' ? 'fila-inactiva d-none' : '' }}" data-id="{{ $disp->id_disponibilidad }}">
                        <td class="dia">{{ $disp->dia_semana }}</td>
                        <td class="inicio">{{ \Carbon\Carbon::parse($disp->hora_inicio)->format('g:i A') }}</td>
                        <td class="fin">{{ \Carbon\Carbon::parse($disp->hora_fin)->format('g:i A') }}</td>
                        <td class="estado">
                            <button class="btn btn-sm toggle-estado {{ $disp->estado === 'ACTIVO' ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                {{ $disp->estado }}
                            </button>
                        </td>
                        <td class="acciones">
                            <button class="btn btn-sm btn-outline-primary editar-horario">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr>

        <h6 class="text-primary"><i class="fas fa-plus-circle me-1"></i>Registrar nuevo horario</h6>
        <form id="formNuevoHorario" method="POST" action="{{ route('perfil.disponibilidad.guardar') }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small"><i class="fas fa-calendar-alt me-1"></i>Día</label>
                    <select id="nuevoDia" name="dias[]" class="form-select" required>

                        <option value="">Seleccionar día</option>
                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                            <option value="{{ $dia }}">{{ $dia }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small"><i class="fas fa-hourglass-start me-1"></i>Hora inicio</label>
                    <select id="nuevoInicio" name="hora_inicio[]" class="form-select scroll-select" required>

                        @for ($h = 7; $h <= 21; $h++)
                            <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00:00">{{ date('g:i A', strtotime("$h:00")) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small"><i class="fas fa-hourglass-end me-1"></i>Hora fin</label>
                    <select id="nuevoFin" name="hora_fin[]" class="form-select scroll-select" required>

                        @for ($h = 8; $h <= 22; $h++)
                            <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00:00">{{ date('g:i A', strtotime("$h:00")) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary mt-1"><i class="fas fa-save me-1"></i>Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal editar horario -->
<div class="modal fade" id="modalEditarHorario" tabindex="-1" aria-labelledby="modalEditarHorarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="formEditarHorario" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-edit me-1"></i> Editar horario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <input type="hidden" id="editId">

        <div class="col-md-12">
          <label class="form-label small">Día</label>
          <select class="form-select" id="editDia">
            @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                <option value="{{ $dia }}">{{ $dia }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label small">Hora inicio</label>
          <select class="form-select" id="editInicio">
            @for ($h = 7; $h <= 21; $h++)
                <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00:00">{{ date('g:i A', strtotime("$h:00")) }}</option>
            @endfor
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label small">Hora fin</label>
          <select class="form-select" id="editFin">
            @for ($h = 8; $h <= 22; $h++)
                <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00:00">{{ date('g:i A', strtotime("$h:00")) }}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Guardar cambios</button>
      </div>
    </form>
  </div>
</div>



</div>



<!-- Toast para notificaciones -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="notificacionToast" class="toast align-items-center border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center">
        <i class="fas fa-check-circle me-2 fs-5"></i>
        <strong>¡Éxito!</strong>&nbsp;Datos actualizados correctamente.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>

<!-- Scripts para edición en línea -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const form = document.getElementById('perfilForm');
    const formURL = form.getAttribute('action');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value;
    
    // Botones para mostrar formularios de edición
    document.querySelectorAll('.editar-campo').forEach(btn => {
        btn.addEventListener('click', function() {
            const campo = this.dataset.campo;
            document.querySelector(`#vista-${campo}`).classList.add('d-none');
            document.querySelector(`#edicion-${campo}`).classList.remove('d-none');
        });
    });
    
    // Botones para cancelar edición
    document.querySelectorAll('.cancelar-edicion').forEach(btn => {
        btn.addEventListener('click', function() {
            const campo = this.dataset.campo;
            document.querySelector(`#vista-${campo}`).classList.remove('d-none');
            document.querySelector(`#edicion-${campo}`).classList.add('d-none');
            
            // Resetear errores
            if (campo === 'password') {
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
                document.getElementById('password').classList.remove('is-invalid');
                document.getElementById('password_confirmation').classList.remove('is-invalid');
            }
        });
    });
    
    // Botones para guardar cambios
    document.querySelectorAll('.guardar-campo').forEach(btn => {
        btn.addEventListener('click', function() {
            const campo = this.dataset.campo;
            
            // Reiniciar estados de validación si hay campos con errores previos
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            
            // Crear objeto FormData con token CSRF y datos del formulario
            const formData = new FormData();
            formData.append('_token', csrfToken);
            
            // Agregar los datos según el campo
            if (campo === 'nombre') {
                formData.append('nombre', document.getElementById('nombre').value);
                formData.append('apellido', document.getElementById('apellido').value);
            } else if (campo === 'fecha') {
                formData.append('fechaNacimiento', document.getElementById('fechaNacimiento').value);
            } else if (campo === 'semestre') {
                formData.append('semestre', document.getElementById('semestre').value);
            } else if (campo === 'genero') {
                formData.append('id_genero', document.getElementById('id_genero').value);
            } else if (campo === 'correo') {
                formData.append('correo', document.getElementById('correo').value);
            } else if (campo === 'password') {
                formData.append('password', document.getElementById('password').value);
                formData.append('password_confirmation', document.getElementById('password_confirmation').value);
            }
            
            // Mostrar indicador de carga
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;
            
            // Enviar solicitud AJAX
            console.log('Enviando solicitud a:', formURL);
            
            fetch(formURL, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error de respuesta:', response.status, errorText);
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Restaurar botón
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.disabled = false;
                
                if (data.success) {
                    // Mostrar notificación de éxito
                    const toastEl = document.getElementById('notificacionToast');
                    toastEl.classList.remove('bg-danger', 'text-white');
                    toastEl.classList.add('bg-success', 'text-white');
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                    
                    // Si hay datos actualizados, actualizar la vista
                    if (data.usuario) {
                        // Actualizar la interfaz según el campo editado
                        if (campo === 'nombre') {
                            const nombreCompleto = `${data.usuario.nombre} ${data.usuario.apellido}`;
                            document.querySelector('#vista-nombre .info-text').textContent = nombreCompleto;
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Nombre actualizado correctamente.';
                        } else if (campo === 'fecha') {
                            document.querySelector('#vista-fecha .info-text').textContent = data.usuario.fechaNacimiento;
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Fecha de nacimiento actualizada correctamente.';
                        } else if (campo === 'semestre') {
                            const semestreCirculo = document.querySelector('#vista-semestre .semester-circle');
                            semestreCirculo.textContent = data.usuario.semestre;
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Semestre actualizado correctamente.';
                        } else if (campo === 'genero') {
                            document.querySelector('#vista-genero .info-text').textContent = data.usuario.genero.genero;
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Género actualizado correctamente.';
                        } else if (campo === 'correo') {
                            document.querySelector('#vista-correo .info-text').textContent = data.usuario.correo;
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Correo electrónico actualizado correctamente.';
                        } else if (campo === 'password') {
                            document.querySelector('.toast-body').innerHTML = '<i class="fas fa-check-circle me-2 fs-5"></i><strong>¡Éxito!</strong>&nbsp;Contraseña actualizada correctamente.';
                        }
                    }
                    
                    // Ocultar formulario de edición
                    document.querySelector(`#edicion-${campo}`).classList.add('d-none');
                    document.querySelector(`#vista-${campo}`).classList.remove('d-none');
                    
                    // Si el campo era contraseña, limpiar los campos
                    if (campo === 'password') {
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';
                    }
                } else {
                    // Mostrar errores si hay
                    if (data.message) {
                        // Mostrar mensaje de error en el toast
                        const toastEl = document.getElementById('notificacionToast');
                        toastEl.classList.remove('bg-success');
                        toastEl.classList.add('bg-danger', 'text-white');
                        document.querySelector('.toast-body').innerHTML = `<i class="fas fa-exclamation-circle me-2 fs-5"></i><strong>Error:</strong>&nbsp;${data.message}`;
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                        
                        // Para campos específicos, mostrar el error inline también
                        if (campo === 'password') {
                            document.getElementById('password').classList.add('is-invalid');
                            const errorElement = document.getElementById('password-error');
                            if (errorElement) {
                                errorElement.textContent = data.message;
                            }
                        }
                    } else {
                        // Error genérico
                        const toastEl = document.getElementById('notificacionToast');
                        toastEl.classList.remove('bg-success');
                        toastEl.classList.add('bg-danger', 'text-white');
                        document.querySelector('.toast-body').innerHTML = '<i class="fas fa-exclamation-circle me-2 fs-5"></i><strong>Error:</strong>&nbsp;Ocurrió un error inesperado.';
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                    }
                }
            })
            .catch(error => {
                // Restaurar botón
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.disabled = false;
                
                console.error('Error:', error);
                
                // Mostrar un toast de error con mensaje específico según el tipo de error
                const toastEl = document.getElementById('notificacionToast');
                toastEl.classList.remove('bg-success');
                toastEl.classList.add('bg-danger', 'text-white');
                
                let errorMessage = 'Error de conexión. Por favor, inténtalo de nuevo.';
                
                // Identificar tipos específicos de errores
                if (error.message && error.message.includes('NetworkError')) {
                    errorMessage = 'Error de red. Verifica tu conexión a internet.';
                } else if (error.message && error.message.includes('timeout')) {
                    errorMessage = 'La solicitud ha excedido el tiempo de espera. Inténtalo de nuevo.';
                } else if (error.message && error.message.includes('419')) {
                    errorMessage = 'Tu sesión ha expirado. Recarga la página e inténtalo de nuevo.';
                    // Refrescar el token CSRF automáticamente
                    fetch('/csrf-token')
                        .then(response => response.json())
                        .then(data => {
                            csrfToken = data.token;
                            document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);
                        });
                }
                
                document.querySelector('.toast-body').innerHTML = `<i class="fas fa-exclamation-circle me-2 fs-5"></i><strong>Error:</strong>&nbsp;${errorMessage}`;
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        });
    });
    
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let modal = new bootstrap.Modal(document.getElementById('modalEditarHorario'));

    document.getElementById('toggleInactivos').addEventListener('change', function () {
        document.querySelectorAll('.fila-inactiva').forEach(f => f.classList.toggle('d-none', !this.checked));
    });

    document.querySelectorAll('.toggle-estado').forEach(btn => {
        btn.addEventListener('click', async function () {
            const tr = this.closest('tr');
            const id = tr.dataset.id;

            const res = await fetch(`/perfil/disponibilidad/${id}/estado`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': csrf }
            });

            if (res.ok) {
                this.innerText = this.innerText === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
                this.classList.toggle('btn-outline-success');
                this.classList.toggle('btn-outline-secondary');
                tr.classList.toggle('fila-inactiva');
            }
        });
    });

    document.getElementById('formNuevoHorario').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);

        const res = await fetch(this.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: fd
        });

        if (res.ok) location.reload();
    });

    document.querySelectorAll('.editar-horario').forEach(btn => {
        btn.addEventListener('click', function () {
            const tr = this.closest('tr');
            document.getElementById('editId').value = tr.dataset.id;
            document.getElementById('editDia').value = tr.querySelector('.dia').textContent.trim();
            document.getElementById('editInicio').value = convertToTime(tr.querySelector('.inicio').textContent.trim());
            document.getElementById('editFin').value = convertToTime(tr.querySelector('.fin').textContent.trim());
            modal.show();
        });
    });

    document.getElementById('formEditarHorario').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('editId').value;

        const res = await fetch(`/perfil/disponibilidad/${id}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                dia_semana: document.getElementById('editDia').value,
                hora_inicio: document.getElementById('editInicio').value,
                hora_fin: document.getElementById('editFin').value
            })
        });

        if (res.ok) location.reload();
    });

    function convertToTime(str) {
        const [hora, ampm] = str.split(' ');
        let [h, m] = hora.split(':');
        h = parseInt(h);
        if (ampm === 'PM' && h < 12) h += 12;
        if (ampm === 'AM' && h === 12) h = 0;
        return `${h.toString().padStart(2, '0')}:${m}:00`;
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const horarios = @json($usuario->disponibilidades);

    const selectDia = document.getElementById('nuevoDia');
    const selectInicio = document.getElementById('nuevoInicio');
    const selectFin = document.getElementById('nuevoFin');

    selectDia?.addEventListener('change', function () {
        const diaSeleccionado = this.value;

        // Resetear selects
        selectInicio.innerHTML = '';
        selectFin.innerHTML = '';

        // Rango total de horas permitidas
        const horas = [];
        for (let h = 7; h <= 21; h++) {
            const horaStr = `${String(h).padStart(2, '0')}:00:00`;
            horas.push(horaStr);
        }

        // Filtrar los horarios ocupados del día
        const ocupados = horarios
            .filter(h => h.dia_semana === diaSeleccionado)
            .map(h => [h.hora_inicio, h.hora_fin]);

        // Filtrar horas disponibles
        const disponibles = horas.filter(h => {
            return !ocupados.some(([inicio, fin]) => h >= inicio && h < fin);
        });

        // Llenar select de inicio
        disponibles.forEach(h => {
            const label = formatHora(h);
            const opt = document.createElement('option');
            opt.value = h;
            opt.textContent = label;
            selectInicio.appendChild(opt);
        });

        // Llenar select de fin (mayor a inicio dinámicamente al elegir)
        selectInicio.addEventListener('change', function () {
            const horaSeleccionada = this.value;
            selectFin.innerHTML = '';
            disponibles.forEach(h => {
                if (h > horaSeleccionada) {
                    const opt = document.createElement('option');
                    opt.value = h;
                    opt.textContent = formatHora(h);
                    selectFin.appendChild(opt);
                }
            });
        });

        // Disparar una vez para inicializar fin
        selectInicio.dispatchEvent(new Event('change'));
    });

    function formatHora(hora) {
        const [h, m] = hora.split(':');
        const hNum = parseInt(h);
        const ampm = hNum >= 12 ? 'PM' : 'AM';
        const h12 = hNum % 12 === 0 ? 12 : hNum % 12;
        return `${h12}:${m} ${ampm}`;
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const horarios = @json($usuario->disponibilidades);

    const editDia = document.getElementById('editDia');
    const editInicio = document.getElementById('editInicio');
    const editFin = document.getElementById('editFin');
    const editId = document.getElementById('editId');

    // Al abrir el modal, ya estamos cargando los valores, ahora solo filtramos
    editDia.addEventListener('change', actualizarHorasEditables);
    editInicio.addEventListener('change', actualizarFinEditable);

    function actualizarHorasEditables() {
        const dia = editDia.value;
        const idActual = editId.value;

        const horas = [];
        for (let h = 7; h <= 21; h++) {
            horas.push(`${String(h).padStart(2, '0')}:00:00`);
        }

        const ocupados = horarios
            .filter(h => h.dia_semana === dia && h.id_disponibilidad != idActual)
            .map(h => [h.hora_inicio, h.hora_fin]);

        const disponibles = horas.filter(h => {
            return !ocupados.some(([inicio, fin]) => h >= inicio && h < fin);
        });

        // Guardar hora seleccionada para no perderla al cambiar día
        const valorActualInicio = editInicio.value;
        const valorActualFin = editFin.value;

        editInicio.innerHTML = '';
        disponibles.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h;
            opt.textContent = formatHora(h);
            if (h === valorActualInicio) opt.selected = true;
            editInicio.appendChild(opt);
        });

        actualizarFinEditable();
    }

    function actualizarFinEditable() {
        const horaInicio = editInicio.value;
        const horaFinActual = editFin.value;

        const opcionesDisponibles = [...editInicio.options].map(opt => opt.value);
        const opcionesFin = opcionesDisponibles.filter(h => h > horaInicio);

        editFin.innerHTML = '';
        opcionesFin.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h;
            opt.textContent = formatHora(h);
            if (h === horaFinActual) opt.selected = true;
            editFin.appendChild(opt);
        });
    }

    function formatHora(hora) {
        const [h, m] = hora.split(':');
        const hNum = parseInt(h);
        const ampm = hNum >= 12 ? 'PM' : 'AM';
        const h12 = hNum % 12 === 0 ? 12 : hNum % 12;
        return `${h12}:${m} ${ampm}`;
    }

    // También actualiza al abrir modal
    document.querySelectorAll('.editar-horario').forEach(btn => {
        btn.addEventListener('click', () => {
            setTimeout(actualizarHorasEditables, 200); // Espera a que se cargue el valor en editDia
        });
    });
});
</script>




@endsection
