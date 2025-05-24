@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📘 Mis Asesorías</h2>
                <p class="text-white-50">Consulta y administra tus asesorías activas</p>
            </div>
            <div class="d-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                <span class="fw-medium">Total: <span class="badge bg-primary rounded-pill">{{ $asesorias->count() }}</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Éxito!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Botón para agregar una nueva asesoría --}}
    <div class="text-end mb-4">
        <a href="{{ route('asesorias.solicitar.get') }}" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-1"></i> Nueva Asesoría
        </a>
    </div>

    {{-- Lista de asesorías activas --}}
    <div class="card shadow-sm border-0 rounded-lg mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-0">
                    <i class="fas fa-list text-primary me-2"></i>
                    Asesorías Activas
                </h3>
                <div class="badge bg-primary rounded-pill px-3 py-2">{{ $asesorias->count() }}</div>
            </div>
        </div>
        <div class="card-body py-4 px-4">
            @if ($asesorias->count() > 0)
                <div class="row g-4 mb-6">
                    @foreach($asesorias as $asesoria)
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="card card-asesoria flex-fill shadow-sm position-relative">
                                {{-- Cinta para el estado de la asesoría --}}
                                <div class="ribbon-wrapper">
                                    <div class="ribbon 
                                        @if($asesoria->estado == 'PENDIENTE') bg-warning text-dark 
                                        @elseif($asesoria->estado == 'CONFIRMADA') bg-success 
                                        @elseif($asesoria->estado == 'PROCESO') bg-info 
                                        @elseif($asesoria->estado == 'FINALIZADA') bg-secondary 
                                        @elseif($asesoria->estado == 'CANCELADA') bg-danger 
                                        @endif">
                                        {{ ucfirst(strtolower($asesoria->estado)) }}
                                    </div>
                                </div>
                                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #004085, #0069d9); color: white;">
                                    <h5 class="card-title mb-0">{{ $asesoria->tema }}</h5>
                                    <p class="mb-0">{{ $asesoria->materia->nombre }}</p>
                                </div>
                                <div class="card-body p-3">
                                    <p class="info-label">Fecha</p>
                                    <p class="info-text">{{ $asesoria->fecha }}</p>
                                    <p class="info-label">Duración</p>
                                    <p class="info-text">
                                        @if($asesoria->duracion == '01:00:00')
                                            1 HORA
                                        @elseif($asesoria->duracion == '02:00:00')
                                            2 HORAS
                                        @else
                                            {{ $asesoria->duracion }}
                                        @endif
                                    </p>
                                    <p class="info-label">Asesor</p>
                                    <p class="info-text">{{ $asesoria->asesor->nombre }} {{ $asesoria->asesor->apellido }}</p>
                                    
                                    <p class="info-label">Mi Estilo de Aprendizaje</p>
                                    <p class="info-text">
                                        @if(Auth::user()->tipo_aprendizaje)
                                            <span class="badge bg-info">{{ Auth::user()->tipo_aprendizaje }}</span>
                                        @else
                                            <span class="badge bg-secondary">No registrado</span>
                                            <a href="{{ route('perfil.show') }}" class="text-primary ms-2 small">
                                                <i class="fas fa-pencil-alt"></i> Registrar
                                            </a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="empty-state-title">No tienes asesorías activas</h4>
                        <p class="empty-state-description">
                            Usa el botón "Nueva Asesoría" para programar una.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection