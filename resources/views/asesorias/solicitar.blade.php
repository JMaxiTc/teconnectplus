@extends('components.layout')

@section('content')
<div class="header-container bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-clipboard-list fa-2x"></i>
            </div>
            <div>
                <h2 class="mb-0 fw-bold">Solicitar Asesoría</h2>
                <p class="mb-0 text-white-50">Completa el formulario para programar una asesoría con un profesor</p>
            </div>
            <div class="ms-auto bg-white text-primary rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-users me-2"></i>
                <span class="fw-bold">Asesores disponibles: <span class="badge bg-primary rounded-pill" id="asesorCount">0</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card border-0 shadow-sm mb-5 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Formulario de Solicitud
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('asesorias.solicitar.post') }}" method="POST" id="formAsesoria">
                        @csrf
                        <div class="row">
                            <!-- Primera columna -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-book text-primary me-2"></i>
                                        <span>Materia</span>
                                    </label>
                                    <select name="materia" id="materiaSelect" class="form-select border-0 bg-light shadow-sm">
                                        <option value="">-- Seleccione una materia --</option>
                                        @foreach ($materias as $materia)
                                            <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('materia')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <span>Asesor disponible</span>
                                    </label>
                                    <select name="asesor" id="asesorSelect" class="form-select border-0 bg-light shadow-sm" disabled>
                                        <option value="">-- Seleccione una materia primero --</option>
                                    </select>
                                    @error('asesor')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-edit text-primary me-2"></i>
                                        <span>Tema de la asesoría</span>
                                    </label>
                                    <input type="text" name="tema" class="form-control border-0 bg-light shadow-sm" 
                                        placeholder="Describe brevemente el tema que deseas consultar">
                                    @error('tema')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Segunda columna -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span>Fecha de asesoría</span>
                                    </label>
                                    <input type="date" name="fecha" class="form-control border-0 bg-light shadow-sm" min="{{ date('Y-m-d') }}">
                                    @error('fecha')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span>Hora de asesoría</span>
                                    </label>
                                    <input type="time" name="hora" class="form-control border-0 bg-light shadow-sm">
                                    @error('hora')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="fas fa-hourglass-half text-primary me-2"></i>
                                        <span>Duración</span>
                                    </label>
                                    <select name="duracion" class="form-select border-0 bg-light shadow-sm">
                                        <option value="30">30 minutos</option>
                                        <option value="45">45 minutos</option>
                                        <option value="60" selected>60 minutos</option>
                                        <option value="90">90 minutos</option>
                                    </select>
                                    @error('duracion')
                                        <div class="text-danger small mt-1">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                                <i class="fas fa-paper-plane"></i>
                                <span>Enviar solicitud</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('materiaSelect').addEventListener('change', function () {
    const materiaId = this.value;
    const asesorSelect = document.getElementById('asesorSelect');
    const asesorCount = document.getElementById('asesorCount');

    if (!materiaId) {
        asesorSelect.innerHTML = '<option value="">-- Seleccione una materia primero --</option>';
        asesorSelect.disabled = true;
        asesorCount.textContent = '0';
        return;
    }
    
    asesorSelect.classList.add('loading');
    
    fetch(`/asesorias/asesores/${materiaId}`)
        .then(response => response.json())
        .then(data => {
            asesorSelect.innerHTML = '';
            if (data.length === 0) {
                asesorSelect.innerHTML = '<option value="">No hay asesores disponibles</option>';
                asesorCount.textContent = '0';
            } else {
                asesorSelect.innerHTML = '<option value="">-- Seleccione un asesor --</option>';
                data.forEach(asesor => {
                    asesorSelect.innerHTML += `<option value="${asesor.id_usuario}">${asesor.nombre} ${asesor.apellido}</option>`;
                });
                asesorCount.textContent = data.length;
            }
            asesorSelect.disabled = false;
            asesorSelect.classList.remove('loading');
        })
        .catch(error => {
            console.error('Error cargando asesores:', error);
            asesorSelect.innerHTML = '<option value="">Error al cargar asesores</option>';
            asesorSelect.classList.remove('loading');
        });
});

// Agregar estilo para indicador de carga
document.head.insertAdjacentHTML('beforeend', `
<style>
    .loading {
        background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzgiIGhlaWdodD0iMzgiIHZpZXdCb3g9IjAgMCAzOCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiM0YTkwZTIiPiAgICA8ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPiAgICAgICAgPGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMSAxKSIgc3Ryb2tlLXdpZHRoPSIyIj4gICAgICAgICAgICA8Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuMyIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+ICAgICAgICAgICAgPHBhdGggZD0iTTM2IDE4YzAtOS45NC04LjA2LTE4LTE4LTE4Ij4gICAgICAgICAgICAgICAgPGFuaW1hdGVUcmFuc2Zvcm0gICAgICAgICAgICAgICAgICAgIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgICAgICAgICAgICAgICAgICAgIHR5cGU9InJvdGF0ZSIgICAgICAgICAgICAgICAgICAgIGZyb209IjAgMTggMTgiICAgICAgICAgICAgICAgICAgICB0bz0iMzYwIDE4IDE4IiAgICAgICAgICAgICAgICAgICAgZHVyPSIxcyIgICAgICAgICAgICAgICAgICAgIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIi8+ICAgICAgICAgICAgPC9wYXRoPiAgICAgICAgPC9nPiAgICA8L2c+PC9zdmc+');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px 20px;
    }
    
    .header-container {
        background-color: #1a73e8 !important;
        border-radius: 0 0 20px 20px;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
    }
    
    .btn-primary {
        background-color: #1a73e8;
        border-color: #1a73e8;
    }
    
    .btn-primary:hover {
        background-color: #1565d6;
        border-color: #1565d6;
    }
</style>
`);
</script>
@endsection
