@extends('components.layout')

@section('content')
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Encabezado -->
            <div class="header-materia d-flex align-items-center p-4 mb-4 shadow-sm">
                <i class="{{ App\Helpers\MateriaHelper::getIconForMateria($materia->nombre) }} fa-4x me-4 text-white"></i>
                <div>
                    <h2 class="mb-1 text-white">{{ $materia->nombre }}</h2>
                    <p class="mb-0 text-white-50">{{ $materia->descripcion }}</p>
                </div>
            </div>

            <!-- Alerta de éxito -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            <!-- Botón para mostrar formulario -->
             @if(Auth::user()->esAdmin() || Auth::user()->esAsesor())
            <div class="text-end mb-3">
                <button id="btnMostrarFormulario" class="btn btn-agregar">Agregar</button>
            </div>
            @endif

            <!-- Formulario oculto -->
            <div id="formularioMaterial" class="card mb-4 shadow border-0" style="display: none;">
                <div class="card-body">
                    <form action="{{ route('materiales.guardar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_materia" value="{{ $materia->id_materia }}">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del material</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="documento">Documento</option>
                                <option value="video">Video</option>
                                <option value="enlace">Enlace</option>
                            </select>
                        </div>

                        <div class="mb-3" id="campoArchivo">
                            <label for="archivo" class="form-label">Archivo</label>
                            <input type="file" name="archivo" class="form-control">
                        </div>

                        <div class="mb-3" id="campoEnlace" style="display: none;">
                            <label for="url" class="form-label">URL del recurso</label>
                            <input type="url" name="url" class="form-control">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <button type="button" class="btn btn-secondary" onclick="ocultarFormulario()">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tarjeta principal de materiales -->
            <div class="card shadow border-0 card-materiales">
                <div class="card-header bg-materia text-white text-center rounded-top">
                    <h3 class="mb-0">Materiales</h3>
                </div>
                <div class="card-body p-4">
                    @if($materiales->isEmpty())
                        <div class="text-center text-muted py-4">No hay materiales disponibles para esta materia.</div>
                    @else
                        <div class="row">
                            @foreach($materiales as $material)
                                <div class="col-md-6 col-lg-4 mb-4 d-flex">
                                    <div class="material-card d-flex shadow-sm w-100">
                                        <!-- Mitad izquierda -->
                                        <div class="left-side d-flex flex-column align-items-center justify-content-center">
                                            <i class="material-icon fas 
                                                @if($material->tipo == 'documento') fa-file-alt 
                                                @elseif($material->tipo == 'video') fa-video 
                                                @else fa-link 
                                                @endif"></i>
                                            <span class="badge tipo-material {{ $material->tipo }} mt-2">{{ $material->tipo }}</span>
                                        </div>

                                        <!-- Mitad derecha -->
                                        <div class="right-side d-flex flex-column justify-content-between p-3">
                                            <h6 class="fw-bold text-dark-blue mb-2">{{ $material->nombre }}</h6>
                                            <div class="text-end">
                                                <a href="{{ asset($material->url) }}" target="_blank" class="btn btn-ver">Ver</a>
                                            </div>
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

<script>
    const btn = document.getElementById('btnMostrarFormulario');
    const form = document.getElementById('formularioMaterial');
    const tipoSelect = document.querySelector('select[name="tipo"]');
    const campoArchivo = document.getElementById('campoArchivo');
    const campoEnlace = document.getElementById('campoEnlace');

    btn.addEventListener('click', () => {
        form.style.display = 'block';
        btn.style.display = 'none';
    });

    function ocultarFormulario() {
        form.style.display = 'none';
        btn.style.display = 'inline-block';
    }

    tipoSelect.addEventListener('change', (e) => {
        if (e.target.value === 'enlace') {
            campoEnlace.style.display = 'block';
            campoArchivo.style.display = 'none';
        } else {
            campoArchivo.style.display = 'block';
            campoEnlace.style.display = 'none';
        }
    });
</script>

<style>
    .header-materia {
        background: linear-gradient(135deg, rgb(42, 121, 200), rgb(20, 74, 128));
        border-radius: 0.75rem;
        color: white;
    }

    .card-materiales {
        border-radius: 1rem;
        overflow: hidden;
    }

    .bg-materia {
        background-color: rgb(42, 93, 145);
    }

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

    .tipo-material.documento {
        background-color: #007bff;
    }

    .tipo-material.video {
        background-color: #dc3545;
    }

    .tipo-material.enlace {
        background-color:rgb(140, 140, 140);
    }

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
