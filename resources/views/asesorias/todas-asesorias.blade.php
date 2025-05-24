@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📋 Historial de Asesorías</h2>
                <p class="text-white">Historial de asesorías finalizadas y canceladas</p>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-lg mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-0">
                    <i class="fas fa-history text-primary me-2"></i>
                    Historial
                </h3>
                <div>
                    <a href="{{ route('asesoriasa.activas.get') }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-calendar-check me-1"></i> Asesorías Activas
                    </a>
                    <a href="{{ route('asesoriasa.solicitudes.get') }}" class="btn btn-sm btn-outline-warning position-relative">
                        Ver Solicitudes
                        <span id="pending-count" class="position-absolute top-0 start-100 translate-middle badge bg-primary rounded-pill">
                            0
                        </span>
                    </a>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        fetch("{{ route('asesoriasa.solicitudes.count') }}")
                            .then(response => response.json())
                            .then(data => {
                                const badge = document.getElementById('pending-count');
                                badge.textContent = data.count;
                                badge.classList.add('pulse'); // Add animation class
                            });
                    });
                </script>
            </div>
        </div>
        <div class="card-body py-4 px-4">
            @if($asesorias->count() > 0)
                <div class="row g-4">
                    @foreach($asesorias as $asesoria)
                        @if(in_array($asesoria->estado, ['FINALIZADA', 'CANCELADA']))
                            <div class="col-md-6 col-lg-4 mb-5">
                                <div class="card solicitud-card h-100">
                                    <div class="ribbon-wrapper">
                                        @if($asesoria->estado === 'FINALIZADA')
                                            <div class="ribbon bg-secondary">Finalizada</div>
                                        @elseif($asesoria->estado === 'CANCELADA')
                                            <div class="ribbon bg-danger">Cancelada</div>
                                        @endif
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
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Fecha</span>
                                                    <p class="info-text">{{ date('d/m/Y H:i', strtotime($asesoria->fecha)) }}</p>
                                                </div>
                                            </div>
                                            <div class="info-item1">
                                                <div class="info-icon">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Estudiante</span>
                                                    <p class="info-text">{{ $asesoria->estudiante->nombre }} {{ $asesoria->estudiante->apellido }}</p>
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
                                        @if($asesoria->estado === 'CANCELADA' && $asesoria->observaciones)
                                        <div class="alert alert-danger mt-3 mb-0">
                                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Motivo de cancelación:</h6>
                                            <p class="mb-0">{{ $asesoria->observaciones }}</p>
                                        </div>
                                        @endif
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
                        <h4 class="empty-state-title">No hay asesorías registradas</h4>
                        <p class="empty-state-description">
                            Aquí aparecerán todas las asesorías gestionadas.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
