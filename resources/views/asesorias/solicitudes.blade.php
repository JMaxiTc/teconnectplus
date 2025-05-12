@extends('components.layout')

@section('content')
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📝 Solicitudes de Asesoría</h2>
                <p class="text-white">Gestiona las solicitudes de asesoría de tus estudiantes</p>
            </div>
            <div class="d-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-clipboard-check text-primary me-2"></i>
                <span class="fw-medium">Total: <span class="badge bg-primary rounded-pill">{{ $asesorias->count() }}</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    
    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Éxito!</strong> {{ session('success') }}
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
                <div class="badge bg-primary rounded-pill px-3 py-2">{{ $asesorias->count() }}</div>
            </div>
        </div>
        <div class="card-body py-4 px-4">
            @if($asesorias->count() > 0)
                <div class="row g-4">
                    @foreach($asesorias as $asesoria)                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="card solicitud-card h-100">
                            <div class="ribbon-wrapper">
                                <div class="ribbon">Pendiente</div>
                            </div>                            <div class="card-header text-white">
                                <h5 class="card-title">{{ $asesoria->materia->nombre }}</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="info-section">
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Tema</span>
                                            <p class="info-text">{{ $asesoria->tema }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div class="info-content">
                                            <span class="info-label">Estudiante</span>
                                            <p class="info-text">{{ $asesoria->estudiante->nombre }} {{ $asesoria->estudiante->apellido }}</p>
                                            <span class="info-subtext">{{ $asesoria->estudiante->carrera }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
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
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex gap-3 justify-content-between">
                                    <form action="{{ route('asesorias.actualizar', $asesoria->id_asesoria) }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="estado" value="aceptada">
                                        <button type="submit" class="btn btn-action btn-accept w-100">
                                            <i class="fas fa-check-circle me-2"></i> Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('asesorias.actualizar', $asesoria->id_asesoria) }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="estado" value="rechazada">
                                        <button type="submit" class="btn btn-action btn-reject w-100">
                                            <i class="fas fa-times-circle me-2"></i> Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                @endforeach
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

<style>
    /* Estilos generales */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }

    /* Estilos para tarjetas de solicitud */    
    .solicitud-card {
        position: relative;
        border: none;
        border-radius: 16px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
    }
    
    .solicitud-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
    }
      /* Encabezado principal de la vista */
    .header-container {
        background: linear-gradient(135deg, #1a5276, #2980b9, #3498db);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }    /* Header de la tarjeta */
    .solicitud-card .card-header {
        background: linear-gradient(135deg, #1a5276, #2980b9, #3498db);
        border-bottom: none;
        padding: 1.5rem 1rem;
        text-align: center;
    }
    
    .solicitud-card .card-title {
        font-weight: 600;
        font-size: 1.2rem;
        color: white;
        margin: 0 auto;
        word-wrap: break-word;
        text-align: center;
    }/* Estilos para la cinta (ribbon) */
    .ribbon-wrapper {
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        overflow: hidden;
        z-index: 5; /* Lower z-index so it doesn't interfere with the icon */
    }
    
    .ribbon {
        position: absolute;
        top: 30px;
        right: -40px;
        background-color: #f39c12;
        color: white;
        padding: 5px 40px;
        font-size: 0.85rem;
        font-weight: 600;
        transform: rotate(45deg);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Estilos para el contenido */
    .info-section {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(52, 152, 219, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .info-icon i {
        color: #3498db;
        font-size: 1.2rem;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        display: block;
        font-size: 0.8rem;
        color: #7f8c8d;
        margin-bottom: 0.2rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .info-text {
        margin: 0;
        font-weight: 500;
        color: #2c3e50;
        font-size: 1rem;
    }
    
    .info-subtext {
        display: block;
        font-size: 0.85rem;
        color: #95a5a6;
    }
    
    /* Footer y botones */
    .card-footer {
        background-color: #f9fafc;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem;
    }
    
    .btn-action {
        font-weight: 500;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .btn-accept {
        background-color: #27ae60;
        color: white;
    }
    
    .btn-accept:hover {
        background-color: #219653;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(39, 174, 96, 0.2);
    }
    
    .btn-reject {
        background-color: #e74c3c;
        color: white;
    }
    
    .btn-reject:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(231, 76, 60, 0.2);
    }
    
    /* Estado vacío */
    .empty-state {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem 0;
    }
    
    .empty-state-content {
        text-align: center;
        max-width: 400px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(52, 152, 219, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-state-icon i {
        font-size: 2rem;
        color: #3498db;
    }
    
    .empty-state-title {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 0.75rem;
    }
    
    .empty-state-description {
        color: #7f8c8d;
        font-size: 1rem;
    }
    
    /* Efectos adicionales y mejoras visuales */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Efecto de pulso para el contador de notificaciones */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
        }
    }
    
    .badge.bg-primary {
        animation: pulse 2s infinite;
    }
    
    /* Mejora en tarjetas para eventos táctiles */
    @media (hover: none) {
        .solicitud-card:active {
            transform: scale(0.98);
        }
    }
    
    /* Fix para el icono de materia */
    .solicitud-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 30px;
        background: transparent;
        z-index: 1;
    }
</style>
@endsection
