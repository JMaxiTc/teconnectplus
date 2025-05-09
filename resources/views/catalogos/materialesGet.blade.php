@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Encabezado con ícono y descripción -->
            <div class="header-materia d-flex align-items-center p-4 mb-4 shadow-sm">
                <i class="{{ App\Helpers\MateriaHelper::getIconForMateria($materia->nombre) }} fa-4x me-4 text-white"></i>
                <div>
                    <h2 class="mb-1 text-white">{{ $materia->nombre }}</h2>
                    <p class="mb-0 text-white-50">{{ $materia->descripcion }}</p>
                </div>
            </div>

            <!-- Tarjeta principal de materiales -->
            <div class="card shadow border-0 card-materiales">
                <div class="card-header bg-materia text-white text-center rounded-top">
                    <h3 class="mb-0">Materiales Disponibles</h3>
                </div>
                <div class="card-body p-4">
                    @if($materiales->isEmpty())
                        <div class="text-center text-muted py-4">No hay materiales disponibles para esta materia.</div>
                    @else
                        <div class="row">
                            @foreach($materiales as $material)
                                <div class="col-md-6 mb-4 d-flex">
                                    <div class="material-card shadow-sm p-3 w-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="fw-bold text-dark-blue">{{ $material->nombre }}</h5>
                                        </div>
                                        <div class="text-end mt-3">
                                            <a href="{{ $material->url }}" target="_blank" class="btn btn-ver">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .header-materia {
        background: linear-gradient(135deg,rgb(42, 121, 200),rgb(20, 74, 128));
        border-radius: 0.75rem;
        color: white;
    }

    .card-materiales {
        border-radius: 1rem;
        overflow: hidden;
    }

    .bg-materia {
        background-color:rgb(42, 93, 145);
    }

    .material-card {
        background-color: #f8f9fa;
        border-radius: 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left: 5px solidrgb(18, 67, 115);
    }

    .material-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .btn-ver {
        background-color: #001f3f;
        color: white;
        border-radius: 0.5rem;
        padding: 6px 16px;
        text-decoration: none;
    }

    .btn-ver:hover {
        background-color: #003366;
    }

    .text-dark-blue {
        color: #001f3f;
    }
</style>
@endsection
