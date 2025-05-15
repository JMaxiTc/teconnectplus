@extends('components.layout')

@section('title', 'Mis Calificaciones')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="page-title mb-4">Mis Calificaciones</h1><div class="rating-overview">
                <h2>Valoración General</h2>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="average-rating">{{ number_format($promedio, 1) }}</div>
                        <div class="star-display">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($promedio))
                                    <i class="fas fa-star"></i>
                                @elseif ($i - 0.5 <= $promedio)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div>Basado en {{ $todasCalificaciones->count() }} calificaciones</div>
                    </div>
                    <div class="col-md-8">
                        <div class="progress-container mt-4">
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $todasCalificaciones->where('puntuacion', $i)->count();
                                    $percentage = $todasCalificaciones->count() > 0 ? ($count / $todasCalificaciones->count()) * 100 : 0;
                                @endphp
                                <div class="row align-items-center mb-3">
                                    <div class="col-2 text-center">
                                        <span class="star-count">{{ $i }}</span> <i class="fas fa-star" style="color: #FFD700;"></i>
                                    </div>
                                    <div class="col-8">                                <div class="progress rating-progress">
                                            <div class="progress-bar bg-rating-{{ $i }}" role="progressbar" 
                                                style="width: {{ $percentage }}%;" 
                                                aria-valuenow="{{ $percentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">{{ $count }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>                </div>
            </div>
              <h2 class="mb-4">
                <i class="fas fa-comments text-primary me-2"></i>
                Comentarios de estudiantes
            </h2>            {{-- Filtros y ordenamiento --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('asesoriasa.calificaciones') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto">
                            <label class="form-label"><i class="fas fa-filter text-primary me-1"></i> Filtrar por estrellas</label>
                            <select name="estrellas" class="form-select" onchange="this.form.submit()">
                                <option value="">Todas las calificaciones</option>                                @for ($i = 5; $i >= 1; $i--)
                                    @php
                                        $countEstrellas = $todasCalificaciones->where('puntuacion', $i)->count();
                                    @endphp
                                    <option value="{{ $i }}" {{ isset($filtroEstrellas) && $filtroEstrellas == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }} ({{ $countEstrellas }})
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            @if($calificaciones->count() > 0)
                @if($todasCalificaciones->count() < 5 && !isset($filtroEstrellas))
                <div class="alert alert-info mb-4">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">¡Estás comenzando!</h5>
                            <p class="mb-0">Tienes {{ $todasCalificaciones->count() }} calificaciones hasta ahora. A medida que tus estudiantes califiquen tus asesorías, podrás ver aquí todas sus opiniones.</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="rating-cards">@foreach($calificaciones as $calificacion)
                        <div class="rating-card" data-rating="{{ $calificacion->puntuacion }}">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="star-display">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $calificacion->puntuacion)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-date">{{ date('d/m/Y', strtotime($calificacion->created_at ?? now())) }}</span>
                            </div>
                            
                            @if($calificacion->comentario)
                                <div class="rating-comment">
                                    {{ $calificacion->comentario }}
                                </div>
                            @else
                                <div class="rating-comment text-muted">
                                    <em>Sin comentario</em>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                  {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $calificaciones->links('pagination::bootstrap-4') }}
                </div>                  <div class="pagination-info">
                    Mostrando {{ $calificaciones->firstItem() }} a {{ $calificaciones->lastItem() }} de {{ $calificaciones->total() }} calificaciones
                    @if(isset($filtroEstrellas) && $filtroEstrellas)
                        <span class="badge bg-primary ms-2">Filtrado: {{ $filtroEstrellas }} {{ $filtroEstrellas == 1 ? 'estrella' : 'estrellas' }}</span>
                        <a href="{{ route('asesoriasa.calificaciones') }}" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times"></i> Quitar filtro
                        </a>
                    @endif
                </div>
            @else
                <div class="no-ratings">
                    <i class="far fa-star"></i>
                    @if(isset($filtroEstrellas) && $filtroEstrellas)
                        <h3>No hay calificaciones con {{ $filtroEstrellas }} {{ $filtroEstrellas == 1 ? 'estrella' : 'estrellas' }}</h3>
                        <p>No se encontraron resultados con el filtro seleccionado.</p>
                        <a href="{{ route('asesoriasa.calificaciones') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-times me-1"></i> Quitar filtro
                        </a>
                    @else
                        <h3>Aún no tienes calificaciones</h3>
                        <p>Cuando los estudiantes califiquen tus asesorías, las verás aquí.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
