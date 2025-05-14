@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">🎓 Detalle de Asesoría</h2>
                <p class="text-white">Detalles de tu sesión de asesoría</p>
            </div>
            <div>
                @php
                    $rutaVolver = '';
                    $textoVolver = '';
                    
                    if (in_array($asesoria->estado, ['CONFIRMADA', 'PROCESO'])) {
                        $rutaVolver = route('asesorias.index');
                        $textoVolver = 'Volver a asesorías activas';
                    } elseif ($asesoria->estado === 'PENDIENTE') {
                        $rutaVolver = route('asesorias.pendientes.get');
                        $textoVolver = 'Volver a solicitudes';
                    } elseif (in_array($asesoria->estado, ['FINALIZADA', 'CANCELADA'])) {
                        $rutaVolver = route('asesorias.historial.get');
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
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h3 class="text-xl font-semibold text-gray-800 mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Información de la Asesoría
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="info-section">
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Estado</span>
                                <p class="info-text">
                                    @if($asesoria->estado === 'PENDIENTE')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($asesoria->estado === 'CONFIRMADA')
                                        <span class="badge bg-success">Confirmada</span>
                                    @elseif($asesoria->estado === 'PROCESO')
                                        <span class="badge bg-info">En Proceso</span>
                                    @elseif($asesoria->estado === 'FINALIZADA')
                                        <span class="badge bg-secondary">Finalizada</span>
                                    @elseif($asesoria->estado === 'CANCELADA')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
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
                                <i class="fas fa-file-alt"></i>
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
                                <span class="info-label">Fecha</span>
                                <p class="info-text">{{ date('d/m/Y', strtotime($asesoria->fecha)) }}</p>
                            </div>
                        </div>
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Hora</span>
                                <p class="info-text">{{ date('H:i', strtotime($asesoria->fecha)) }}</p>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna de información del asesor -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h3 class="text-xl font-semibold text-gray-800 mb-0">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        Datos del Asesor
                    </h3>
                </div>                <div class="card-body p-4">
                    <div class="info-section">
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Nombre</span>
                                <p class="info-text">{{ $asesoria->asesor->nombre }} {{ $asesoria->asesor->apellido }}</p>
                            </div>
                        </div>
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Correo</span>
                                <p class="info-text">{{ $asesoria->asesor->correo }}</p>
                            </div>
                        </div>
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Carrera</span>
                                <p class="info-text">{{ $asesoria->asesor->carrera }}</p>
                            </div>
                        </div>
                        <div class="info-item1">
                            <div class="info-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Semestre</span>
                                <div class="d-flex align-items-center">
                                    <div class="semester-circle">
                                        <span>{{ $asesoria->asesor->semestre }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna de herramientas y acciones -->
        <div class="col-lg-4 mb-4">
            @if(in_array($asesoria->estado, ['CONFIRMADA', 'PROCESO']))
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="text-xl font-semibold text-gray-800 mb-0">
                            <i class="fas fa-tools text-primary me-2"></i>
                            Herramientas
                        </h3>
                    </div>                    <div class="card-body p-4">                        <div class="d-grid gap-3">
                            <div class="text-center mb-3">
                                @if(!empty($asesoria->videoconference_url) && strpos($asesoria->videoconference_url, 'meet.google.com/') !== false && strpos($asesoria->videoconference_url, 'new') === false)
                                    <span class="badge bg-success p-2 fs-6">Reunión creada por el asesor</span>
                                @else
                                    <span class="badge bg-warning p-2 fs-6">Esperando creación de la reunión</span>
                                @endif
                            </div>
                            
                            @if(!empty($asesoria->videoconference_url) && strpos($asesoria->videoconference_url, 'meet.google.com/') !== false && strpos($asesoria->videoconference_url, 'new') === false)
                                <!-- Si ya hay un enlace guardado -->
                                <a href="{{ $asesoria->videoconference_url }}" target="_blank" class="btn btn-success btn-lg">
                                    <i class="fas fa-video me-2"></i> Unirme a la reunión
                                </a>
                                <p class="small text-muted text-center mt-2">
                                    Enlace de reunión: <strong>{{ $asesoria->videoconference_url }}</strong>
                                </p>
                            @else
                                <!-- Si no hay un enlace guardado -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tu asesor aún no ha creado la sala de reunión. Por favor, espera a que lo haga o comunícate con él.
                                </div>
                            @endif
                        </div>
                            <a href="{{ route('materiales.get', $asesoria->fk_id_materia) }}" class="btn btn-outline-primary">
                                <i class="fas fa-book-open me-2"></i> Materiales de la Materia
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($asesoria->estado === 'PENDIENTE')
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="text-xl font-semibold text-gray-800 mb-0">
                            <i class="fas fa-exclamation-circle text-warning me-2"></i>
                            Estado Pendiente
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning">
                            <p><i class="fas fa-info-circle me-2"></i> Tu solicitud está pendiente de confirmación por parte del asesor.</p>
                        </div>
                        <p class="mb-0">Te notificaremos cuando el asesor confirme o rechace tu solicitud.</p>
                    </div>
                </div>
            @endif

            @if($asesoria->estado === 'CANCELADA')
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="text-xl font-semibold text-gray-800 mb-0">
                            <i class="fas fa-ban text-danger me-2"></i>
                            Asesoría Cancelada
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-danger">
                            <p><i class="fas fa-info-circle me-2"></i> Esta asesoría ha sido cancelada.</p>
                        </div>
                        <p class="mb-0">Si necesitas ayuda, puedes solicitar una nueva asesoría.</p>
                        <div class="d-grid mt-3">
                            <a href="{{ route('asesorias.solicitar.get') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Solicitar nueva asesoría
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($asesoria->estado === 'FINALIZADA')
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="text-xl font-semibold text-gray-800 mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Asesoría Completada
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-success">
                            <p><i class="fas fa-info-circle me-2"></i> Esta asesoría ha sido completada exitosamente.</p>
                        </div>
                        <p class="mb-3">¿Necesitas otra sesión? Puedes solicitar una nueva asesoría.</p>
                        <div class="d-grid">
                            <a href="{{ route('asesorias.solicitar.get') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Solicitar nueva asesoría
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
