@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <button class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#filtroOffcanvas">
                <i class="fas fa-filter me-1"></i> Filtrar
            </button>
        </div>
        @if(auth()->user() && auth()->user()->rol == 'admin')
        <div>
            <a href="{{ route('materias.index') }}" class="btn btn-success">Agregar material</a>
        </div>
        @endif
    </div>

    <div class="mb-3">
        <strong>{{ $materiales->total() }}</strong> resultados encontrados.
    </div>

    <div class="row">
        @forelse($materiales as $material)
            <div class="col-md-6 col-lg-4 mb-4 d-flex">
                <div class="material-card d-flex shadow-sm w-100">
                    <div class="left-side d-flex flex-column align-items-center justify-content-center">
                        <i class="material-icon fas 
                            @if($material->tipo == 'documento') fa-file-alt 
                            @elseif($material->tipo == 'video') fa-video 
                            @else fa-link 
                            @endif"></i>
                        <span class="badge tipo-material {{ $material->tipo }} mt-2">{{ $material->tipo }}</span>
                    </div>
                    <div class="right-side d-flex flex-column justify-content-between p-3">
                        <h6 class="fw-bold text-dark-blue mb-2">{{ $material->nombre }}</h6>
                        <small class="text-muted">{{ $material->materia->nombre }}</small>
                        <div class="text-end">
                            <a href="{{ asset($material->url) }}" target="_blank" class="btn btn-ver">Ver</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">No se encontraron materiales con los filtros seleccionados.</div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $materiales->appends(request()->query())->links() }}
    </div>
</div>

<!-- Offcanvas de Filtros -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filtroOffcanvas" aria-labelledby="filtroOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="filtroOffcanvasLabel">Filtrar materiales</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('materiales.todos') }}" class="d-flex flex-column gap-3">
            <div>
                <label for="materia" class="form-label">Materia</label>
                <select name="materia" id="materia" class="form-select">
                    <option value="">Todas las materias</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id_materia }}" {{ request('materia') == $m->id_materia ? 'selected' : '' }}>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <option value="documento" {{ request('tipo') == 'documento' ? 'selected' : '' }}>Documento</option>
                    <option value="video" {{ request('tipo') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="enlace" {{ request('tipo') == 'enlace' ? 'selected' : '' }}>Enlace</option>
                </select>
            </div>

            <div>
                <label for="busqueda" class="form-label">Buscar por nombre</label>
                <input type="text" name="busqueda" id="busqueda" class="form-control" value="{{ request('busqueda') }}">
            </div>

            <div class="d-flex justify-content-between mt-2">
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                <a href="{{ route('materiales.todos') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Estilos -->
<style>
    .material-card {
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #dbe2ec;
        background-color: white;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        min-height: 130px;
    }
    .material-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.07);
    }
    .left-side {
        background: #3F5EFB;
        background: radial-gradient(circle, rgba(63, 94, 251, 1) 0%, rgba(30, 30, 69, 1) 50%);
        color: white;
        padding: 1rem 0.75rem;
        width: 50%;
        text-align: center;
    }
    .right-side {
        width: 50%;
        background-color: #f8f9fc;
    }
    .material-icon {
        font-size: 2.5rem;
    }
    .tipo-material {
        padding: 0.25rem 0.6rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 999px;
        text-transform: capitalize;
        color: white;
    }
    .tipo-material.documento { background-color: #007bff; }
    .tipo-material.video { background-color: #dc3545; }
    .tipo-material.enlace { background-color:rgb(142, 142, 142); }
    .text-dark-blue {
        color: #001f3f;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .btn-ver {
        background: linear-gradient(90deg, #001f3f, #004080);
        color: white;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: background 0.3s ease;
        border: none;
    }
    .btn-ver:hover {
        background: linear-gradient(90deg, #003366, #0059b3);
    }
</style>
@endsection
