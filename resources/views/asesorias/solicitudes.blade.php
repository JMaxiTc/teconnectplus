@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<!-- Encabezado de la página -->
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📝 Solicitudes de Asesoría</h2>
                <p class="text-white">Revisa y gestiona las solicitudes pendientes</p>
            </div>
            <div class="d-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-clipboard-check text-primary me-2"></i>
                <span class="fw-medium">Total: <span class="badge bg-primary rounded-pill">{{ $asesorias->count() }}</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    
    @if(session('mensaje'))
        <div class="alert alert-{{ session('tipo') }} shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Éxito!</strong> {{ session('mensaje') }}
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-lg mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-0">
                    <i class="fas fa-list-alt text-primary me-2"></i>
                    Solicitudes Pendientes
                </h3>
                <div>
                    <a href="{{ route('asesoriasa.activas.get') }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-calendar-check me-1"></i> Asesorías Activas
                    </a>
                    <a href="{{ route('asesoriasa.historial.get') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Historial
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-4 px-4">
            @if($asesorias->count() > 0)
                <div class="row g-4">
                    @foreach($asesorias as $asesoria)
                        @if($asesoria->estado === 'PENDIENTE')
                            <div class="col-md-6 col-lg-4 mb-5">
                                <div class="card solicitud-card h-100">
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon">Pendiente</div>
                                    </div>
                                    <div class="card-header text-white">
                                        <h5 class="card-title">{{ $asesoria->materia->nombre }}</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="info-section">
                                            <div class="info-item1">
                                                <div class="info-icon">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Tema</span>
                                                    <p class="info-text">{{ $asesoria->tema }}</p>
                                                </div>
                                            </div>
                                            <div class="info-item1">
                                                <div class="info-icon">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Estudiante</span>
                                                    <p class="info-text">{{ $asesoria->estudiante->nombre }} {{ $asesoria->estudiante->apellido }}</p>
                                                    <span class="info-subtext">{{ $asesoria->estudiante->carrera }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item1">
                                                <div class="info-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Fecha solicitada</span>
                                                    <p class="info-text">
                                                        @if($asesoria->fecha)
                                                            {{ date('d/m/Y H:i', strtotime($asesoria->fecha)) }}
                                                        @else
                                                            Fecha no disponible
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="info-item1">
                                                <div class="info-icon">
                                                    <i class="fas fa-brain"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Estilo de Aprendizaje</span>
                                                    @if($asesoria->estudiante->tipo_aprendizaje)
                                                        <span class="badge bg-info">{{ $asesoria->estudiante->tipo_aprendizaje }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">No registrado</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex gap-3 justify-content-between">
                                            <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST" class="w-100">
                                                @csrf
                                                <input type="hidden" name="estado" value="CONFIRMADA">
                                                <button type="submit" class="btn btn-action btn-accept w-100">
                                                    <i class="fas fa-check-circle me-2"></i> Aceptar
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-action btn-reject w-100" data-bs-toggle="modal" data-bs-target="#cancelarAsesoria{{ $asesoria->id_asesoria }}Modal">
                                                <i class="fas fa-times-circle me-2"></i> Rechazar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h4 class="empty-state-title">No hay solicitudes pendientes</h4>
                        <p class="empty-state-description">
                            Cuando los estudiantes soliciten asesorías, aparecerán aquí para que puedas gestionarlas.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales de Cancelación/Rechazo de Asesorías -->
@foreach($asesorias as $asesoria)
    @if($asesoria->estado === 'PENDIENTE')
    <div class="modal fade" id="cancelarAsesoria{{ $asesoria->id_asesoria }}Modal" tabindex="-1" aria-labelledby="cancelarAsesoria{{ $asesoria->id_asesoria }}ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelarAsesoria{{ $asesoria->id_asesoria }}ModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Rechazar Solicitud de Asesoría
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('asesoriasa.actualizar', $asesoria->id_asesoria) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Por favor, proporciona el motivo del rechazo. Esta información será visible para el estudiante.
                        </div>
                        
                        <input type="hidden" name="estado" value="CANCELADA">
                        
                        <div class="mb-3">
                            <label for="observaciones{{ $asesoria->id_asesoria }}" class="form-label">Motivo del rechazo <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="observaciones{{ $asesoria->id_asesoria }}" name="observaciones" rows="4" required></textarea>
                            <div class="form-text">Por favor, sé claro y específico sobre por qué no puedes atender esta solicitud.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Confirmar Rechazo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

@endsection
