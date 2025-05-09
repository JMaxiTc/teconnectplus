@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-4">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h1 class="fw-bold">Materias</h1>
        </div>
        @if(Auth::user()->esAdmin())
            <div class="col-auto">
                <a class="btn btn-agregar" href="{{ url('/catalogos/materias/agregar') }}">Agregar</a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4 mt-2">
        @foreach($materias as $materia)
        <div class="col-md-6 col-lg-4 d-flex">
            <div class="card card-materia flex-fill shadow-sm">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #004085, #0069d9); color: white;">
                    <i class="{{ App\Helpers\MateriaHelper::getIconForMateria($materia->nombre) }} fa-3x mb-2"></i>
                    <h5 class="card-title mb-0">{{ $materia->nombre }}</h5>
                </div>
                <div class="card-body p-3">
                    <p class="card-text text-muted mb-2">{{ $materia->descripcion }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center bg-transparent px-3 py-3">
                    <a href="{{ url('/catalogos/materias/' . $materia->id_materia . '/materiales') }}" class="btn btn-outline-primary rounded-pill px-3">
                        Ver Materiales
                    </a>
                    @if(Auth::user()->esAdmin())
                        <a href="{{ url('/catalogos/materias/' . $materia->id_materia . '/actualizar') }}" class="btn btn-outline-secondary rounded-pill px-3">
                            Actualizar
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .card-materia {
        border: none;
        border-radius: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-materia:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .card-text {
        font-size: 0.95rem;
        color: #6c757d;
    }

    .btn-outline-primary,
    .btn-outline-secondary {
        border-width: 2px;
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        background-color: #0069d9;
        color: white;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-agregar {
        background-color: #198754;
        color: white;
        padding: 8px 20px;
        border-radius: 0.5rem;
    }

    .btn-agregar:hover {
        background-color: #157347;
    }
</style>
@endsection
