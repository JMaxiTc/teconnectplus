@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📊 Mis Asesorías Activas</h2>
                <p class="text-white">Asesorías confirmadas y en proceso</p>
            </div>
            <div class="d-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-calendar-check text-primary me-2"></i>
                <span class="fw-medium">Total: <span class="badge bg-primary rounded-pill">{{ $asesorias->count() }}</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    {{-- Botón para agregar una nueva asesoría --}}
    <div class="text-end mb-4">
        <a href="{{ route('asesorias.solicitar.get') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-1"></i> Nueva Asesoría
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-lg mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-0">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    Asesorías en Curso
                </h3>
                <div>
                    <a href="{{ route('asesorias.pendientes.get') }}" class="btn btn-sm btn-outline-warning me-2">
                        <i class="fas fa-clock me-1"></i> Pendientes
                    </a>
                    <a href="{{ route('asesorias.historial.get') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Historial
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-4 px-4">
            @if($asesorias->count() > 0)
                <div class="row g-4">
                    @foreach($asesorias as $asesoria)
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="card solicitud-card h-100">
                                <div class="ribbon-wrapper">
                                    @if($asesoria->estado === 'CONFIRMADA')
                                        <div class="ribbon bg-success">Confirmada</div>
                                    @elseif($asesoria->estado === 'PROCESO')
                                        <div class="ribbon bg-info">En Proceso</div>
                                    @endif
                                </div>
                                <div class="card-header text-white">
                                    <h5 class="card-title">{{ $asesoria->materia->nombre }}</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="info-section">
                                        <div class="info-item1">
                                            <div class="info-icon">
                                                <i class="fas fa-user-tie"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Asesor</span>
                                                <p class="info-text">{{ $asesoria->asesor->nombre }} {{ $asesoria->asesor->apellido }}</p>
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
                                                <p class="info-text">{{ date('d/m/Y H:i', strtotime($asesoria->fecha)) }}</p>
                                            </div>
                                        </div>
                                        <div class="info-item1">
                                            <div class="info-icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Duración</span>
                                                <p class="info-text">{{ $asesoria->duracion }}</p>
                                            </div>
                                        </div>
                                        <div class="info-item1">
                                            <div class="info-icon">
                                                <i class="fas fa-brain"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Mi Estilo de Aprendizaje</span>
                                                @if(Auth::user()->tipo_aprendizaje)
                                                    <span class="badge bg-info">{{ Auth::user()->tipo_aprendizaje }}</span>
                                                @else
                                                    <span class="badge bg-secondary">No registrado</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('asesorias.detalle.get', $asesoria->id_asesoria) }}" class="btn btn-primary w-100">
                                            <i class="fas fa-chalkboard-teacher me-2"></i> Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4 class="empty-state-title">No tienes asesorías activas</h4>
                        <p class="empty-state-description">
                            Aquí aparecerán tus asesorías confirmadas y en proceso.
                        </p>
                        <a href="{{ route('asesorias.solicitar.get') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i> Solicitar una asesoría
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
