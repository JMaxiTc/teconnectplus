@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">🎓 Sesión de Asesoría</h2>
                <p class="text-white">Detalles y gestión de la asesoría actual</p>
            </div>            <div>
                @php
                    $rutaVolver = '';
                    $textoVolver = '';
                    
                    if (in_array($asesoria->estado, ['CONFIRMADA', 'PROCESO'])) {
                        $rutaVolver = route('asesoriasa.activas.get');
                        $textoVolver = 'Volver a asesorías activas';
                    } elseif ($asesoria->estado === 'PENDIENTE') {
                        $rutaVolver = route('asesoriasa.solicitudes.get');
                        $textoVolver = 'Volver a solicitudes';
                    } elseif (in_array($asesoria->estado, ['FINALIZADA', 'CANCELADA'])) {
                        $rutaVolver = route('asesoriasa.historial.get');
                        $textoVolver = 'Volver al historial';
                    }
                @endphp
                <a href="{{ $rutaVolver }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ $textoVolver }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Columna de información de la asesoría -->
        <div class="col-lg-4 mb-4">
            <div class="card solicitud-card mb-4">
                <div class="card-header text-white">
                    <h3 class="mb-0 fs-5">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de la Asesoría
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="info-section">
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Materia</span>
                                <p class="info-text">{{ $asesoria->materia->nombre }}</p>
                            </div>
                        </div>
                        
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-clipboard"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Tema</span>
                                <p class="info-text">{{ $asesoria->tema }}</p>
                            </div>
                        </div>
                        
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Fecha y hora</span>
                                <p class="info-text">{{ date('d/m/Y', strtotime($asesoria->fecha)) }} 
                                    <span class="badge bg-info ms-2">
                                        <i class="fas fa-clock me-1"></i> 
                                        {{ date('H:i', strtotime($asesoria->fecha)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Duración</span>
                                <p class="info-text">{{ $asesoria->duracion }}</p>
                            </div>
                        </div>
                        
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Estado</span>
                                <div class="info-text">
                                    @if($asesoria->estado === 'CONFIRMADA')
                                        <span class="badge bg-success fs-6 px-3 py-2">Confirmada</span>
                                    @elseif($asesoria->estado === 'PROCESO')
                                        <span class="badge bg-info fs-6 px-3 py-2">En Proceso</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones para la asesoría -->
            <div class="card solicitud-card">
                <div class="card-header text-white">
                    <h3 class="mb-0 fs-5">
                        <i class="fas fa-cogs me-2"></i>
                        Acciones
                    </h3>
                </div> <div class="card-body">
                    <div class="d-grid gap-3">
                        @if($asesoria->estado === 'CONFIRMADA')
                            <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST">
                                @csrf
                                <input type="hidden" name="estado" value="PROCESO">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-play me-2"></i>
                                    Iniciar Asesoría
                                </button>
                            </form>
                        @endif
                        
                        @if($asesoria->estado === 'PROCESO')
                            <div class="d-flex gap-2">
                                <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="estado" value="FINALIZADA">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Finalizar
                                    </button>
                                </form>
                                  <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="estado" value="CANCELADA">
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelarAsesoriaModal">
                                        <i class="fas fa-times-circle me-2"></i>
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST">
                                @csrf
                                <input type="hidden" name="estado" value="CANCELADA">
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelarAsesoriaModal">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Cancelar Asesoría
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if($asesoria->estado === 'CANCELADA' && $asesoria->observaciones)
            <div class="card solicitud-card mt-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <h3 class="mb-0 fs-5">
                        <i class="fas fa-ban me-2"></i>
                        Motivo de la Cancelación
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="info-item1">
                        <div class="info-icon" style="background-color: rgba(220, 53, 69, 0.1);">
                            <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                        </div>
                        <div class="info-content">
                            <p class="info-text">{{ $asesoria->observaciones }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Columna de información del estudiante y herramientas -->
        <div class="col-lg-8">
            <div class="card solicitud-card mb-4">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <h3 class="mb-0 fs-5">
                        <i class="fas fa-user-graduate me-2"></i>
                        Información del Estudiante
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="info-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-item1">
                                    <div class="info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Nombre</span>
                                        <p class="info-text">{{ $asesoria->estudiante->nombre }} {{ $asesoria->estudiante->apellido }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-item1">
                                    <div class="info-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Carrera</span>
                                        <p class="info-text">{{ $asesoria->estudiante->carrera }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-item1">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Correo Electrónico</span>
                                        <p class="info-text">{{ $asesoria->estudiante->correo }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-item1">
                                    <div class="info-icon">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Semestre</span>
                                        <div class="d-flex mt-1">
                                            <div class="semester-circle d-flex align-items-center justify-content-center text-white rounded-circle" 
                                                 style="width: 45px; height: 45px; font-weight: bold; font-size: 1.25rem; background-color: #17a2b8 !important;">
                                                {{ $asesoria->estudiante->semestre }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Herramientas para la sesión -->
            <div class="card solicitud-card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745, #218838);">
                    <h3 class="mb-0 fs-5">
                        <i class="fas fa-tools me-2"></i>
                        Herramientas para la Sesión
                    </h3>
                </div><div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="tool-card h-100 p-4 border rounded shadow-sm text-center" style="transition: all 0.3s ease;">
                                <div class="info-icon mx-auto mb-3" style="width: 70px; height: 70px; background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="fas fa-video fa-2x" style="color: #0d6efd;"></i>
                                </div>
                                <h4 class="tool-title mb-3">Video Conferencia</h4>
                                <div class="mb-3">
                                    @if(!empty($asesoria->videoconference_url) && strpos($asesoria->videoconference_url, 'meet.google.com/') !== false && strpos($asesoria->videoconference_url, 'new') === false)
                                        <span class="badge bg-success p-2 pulse">Reunión creada</span>
                                    @else
                                        <span class="badge bg-warning p-2">Reunión no creada</span>
                                    @endif
                                </div>
                                
                                @if(!empty($asesoria->videoconference_url) && strpos($asesoria->videoconference_url, 'meet.google.com/') !== false && strpos($asesoria->videoconference_url, 'new') === false)
                                    <!-- Si ya hay un enlace guardado -->
                                    <p class="text-success">¡Reunión creada y lista para usarse!</p>
                                    <a href="{{ $asesoria->videoconference_url }}" target="_blank" class="btn btn-success mt-1">
                                        <i class="fas fa-video me-2"></i>
                                        Unirme a la reunión
                                    </a>
                                    <p class="small text-muted mt-2">
                                        Enlace de reunión: <strong>{{ $asesoria->videoconference_url }}</strong>
                                    </p>
                                @else
                                    <!-- Si no hay un enlace guardado -->
                                    @if($asesoria->estado === 'PROCESO')
                                        <p class="text-muted">Crea una nueva reunión de Google Meet para esta asesoría.</p>
                                        <a href="{{ $videoconferenciaUrl }}" target="_blank" class="btn btn-outline-primary mt-1">
                                            <i class="fas fa-video me-2"></i>
                                            Crear reunión Meet
                                        </a>
                                        <p class="small text-muted mt-2">
                                            1. Crea una nueva reunión con el botón de arriba<br>
                                            2. Una vez creada, copia el enlace y guárdalo abajo<br>
                                            3. También puedes pedirle al estudiante que cree la reunión
                                        </p>                                    
                                        <!-- Formulario para guardar el enlace de Meet -->
                                        <form action="{{ route('asesoriasa.guardar.meet') }}" method="POST" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="id_asesoria" value="{{ $asesoria->id_asesoria }}">
                                            <div class="input-group">
                                                <input type="url" class="form-control" name="enlace_meet" 
                                                    placeholder="Pega aquí el enlace de Google Meet" 
                                                    required pattern="https://meet\.google\.com/.*">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-save"></i> Guardar
                                                </button>
                                            </div>
                                            <div class="form-text text-muted">
                                                Debe comenzar con https://meet.google.com/
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-container-sm bg-warning text-white rounded-circle me-3 p-2">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0">Tienes que iniciar la sesión primero Usando el botón <strong>"Iniciar Asesoría"</strong>.</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <p class="mb-0 text-center">
                                                <i class="fas fa-arrow-left me-1"></i>
                                                Busca el botón en la columna izquierda
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="tool-card h-100 p-4 border rounded shadow-sm text-center">
                                <div class="tool-icon mb-3">
                                    <i class="fas fa-file-alt fa-3x text-success"></i>
                                </div>
                                <h4 class="tool-title">Materiales del Curso</h4>
                                <p class="text-muted">Accede a los materiales disponibles para esta materia.</p>
                                <a href="{{ route('materiales.get', $asesoria->materia->id_materia) }}" class="btn btn-outline-success mt-3">
                                    <i class="fas fa-download me-2"></i>
                                    Ver Materiales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        </div>
    </div>
</div>

<!-- Modal de Cancelación de Asesoría -->
<div class="modal fade" id="cancelarAsesoriaModal" tabindex="-1" aria-labelledby="cancelarAsesoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelarAsesoriaModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cancelar Asesoría
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Por favor, proporciona el motivo de la cancelación. Esta información será visible para el estudiante.
                    </div>
                    
                    <input type="hidden" name="estado" value="CANCELADA">
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Motivo de la cancelación <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="4" required></textarea>
                        <div class="form-text">Por favor, sé claro y específico.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        Confirmar Cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Estilos para el mensaje de alerta de videoconferencia */
.icon-container-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-warning {
    border-left: 4px solid #ffc107;
    background-color: rgba(255, 193, 7, 0.1);
}

.alert-warning .alert-heading {
    color: #856404;
    font-weight: 600;
}

.alert hr {
    margin: 1rem 0;
    opacity: 0.2;
}
</style>
@endsection
