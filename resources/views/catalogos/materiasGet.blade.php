@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-4">
    <div class="row align-items-center">
        <div class="col">
            <h1>Materias</h1>
        </div>
        @if(Auth::user()->esAdmin())
            <div class="col-auto">
                <a class="btn btn-agregar" href="{{ url('/catalogos/materias/agregar') }}">Agregar</a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Contenedor para las tarjetas -->
    <div class="row mt-4">
        @foreach($materias as $materia)
        <div class="col-md-4 mb-4 d-flex">
            <!-- Tarjeta de Materia -->
            <div class="card shadow-sm h-100 w-100 card-materia" >
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $materia->nombre }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $materia->codigo }}</h6>
                    <p class="card-text flex-grow-1">{{ $materia->descripcion }}</p>
                    <div class="d-flex justify-content-between mt-3">
                        @if(Auth::user()->esAdmin())
                            <a href="{{ url('/catalogos/materias/' . $materia->id_materia . '/actualizar') }}" class="btn btn-warning btn-sm">Actualizar</a>
                        @endif
                        <a href="{{ url('/catalogos/materias/' . $materia->id_materia . '/materiales') }}" class="btn btn-info btn-sm">Materiales</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
<style>
    .card-materia {
        min-height: 80px;
        max-height: 240px;
        width: 100%;
        border-radius: 12px;
        border: 1px solid #ddd;
        background-color: #fff; /* conserva tu color blanco */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card-materia:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .card-text {
        font-size: 0.85rem;
        color: #444;
    }

    .btn {
        font-size: 0.8rem;
    }
</style>

@endsection
