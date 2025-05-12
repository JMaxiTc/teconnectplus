@extends('components.layout')

@section('content')
<div class="header-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">📚 Mis Materias</h2>
                <p class="text-white-50">Administra las materias en las que brindas asesoría</p>
            </div>
            <div class="d-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                <i class="fas fa-book-open text-primary me-2"></i>
                <span class="fw-medium">Total: <span class="badge bg-primary rounded-pill">{{ $materiasAsignadas->count() }}</span></span>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <strong>¡Éxito!</strong> {{ session('success') }}
            </div>
        </div>
    @endif
    
    {{-- Botón para asignar nueva materia --}}
    <div class="text-end mb-4">
        <button id="btnAgregarMateria" class="btn btn-primary rounded-pill">
            <i class="fas fa-plus me-1"></i> Agregar Materia
        </button>
    </div>

    {{-- Formulario para asignar nueva materia (inicialmente oculto) --}}
    <div class="card shadow-sm mb-5" id="formNuevaMateria" style="display: none;">
        <div class="card-header bg-primary text-white">
            <h3 class="h5 mb-0">Asignar Nueva Materia</h3>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('asignarMateriaPost') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="fk_id_materia" class="form-label">Selecciona una materia:</label>
                    <select name="fk_id_materia" id="fk_id_materia" required
                        class="form-select">
                        <option value="" disabled selected>-- Selecciona --</option>
                        @foreach ($todasMaterias as $materia)
                            @if (!$materiasAsignadas->pluck('fk_id_materia')->contains($materia->id_materia))
                                <option value="{{ $materia->id_materia }}">{{ $materia->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Asignar Materia
                    </button>
                </div>
            </form>
        </div>
    </div>
      {{-- Sección de materias asignadas --}}
    <div class="card shadow-sm border-0 rounded-lg mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-0">
                    <i class="fas fa-book text-primary me-2"></i>
                    Materias Asignadas
                </h3>
                <div class="badge bg-primary rounded-pill px-3 py-2">{{ $materiasAsignadas->count() }}</div>
            </div>
        </div>
        <div class="card-body py-4 px-4">

    @if ($materiasAsignadas->count() > 0)
        <div class="row g-4 mb-6">
            @foreach($materiasAsignadas as $item)
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card card-materia flex-fill shadow-sm">
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #004085, #0069d9); color: white;">
                        <i class="{{ App\Helpers\MateriaHelper::getIconForMateria($item->materia->nombre) }} fa-3x mb-2"></i>
                        <h5 class="card-title mb-0">{{ $item->materia->nombre }}</h5>
                    </div>
                    <div class="card-body p-3">
                        @if(isset($item->materia->descripcion))
                            <p class="card-text text-muted mb-2">{{ $item->materia->descripcion }}</p>
                        @else
                            <p class="card-text text-muted mb-2">Sin descripción disponible</p>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center bg-transparent px-3 py-3">
                        <form action="{{ route('eliminarMateriaPost', $item->id_asesor_materia) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else            <div class="empty-state">
                <div class="empty-state-content">
                    <div class="empty-state-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h4 class="empty-state-title">No tienes materias asignadas</h4>
                    <p class="empty-state-description">
                        Usa el botón "Agregar Materia" para comenzar a asignar materias a tu perfil.
                    </p>
                </div>
            </div>
        @endif
        </div>
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
    .btn-outline-secondary,
    .btn-outline-danger {
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
      .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }
    
    /* Estilos para el encabezado principal */
    .header-container {
        background: linear-gradient(135deg, #1a5276, #2980b9, #3498db);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }
    
    /* Estilos para los badges y efectos */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Efecto de pulso para el contador */
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
</style>


{{-- Script para manejar el botón de agregar materia --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAgregarMateria = document.getElementById('btnAgregarMateria');
        const formNuevaMateria = document.getElementById('formNuevaMateria');
        
        // Ocultar el formulario inicialmente
        formNuevaMateria.style.display = 'none';
        
        // Manejar el clic en el botón de agregar materia
        btnAgregarMateria.addEventListener('click', function() {
            // Alternar la visibilidad del formulario con un efecto de deslizamiento
            if (formNuevaMateria.style.display === 'none') {
                // Mostrar el formulario
                formNuevaMateria.style.display = 'block';
                formNuevaMateria.style.maxHeight = '0';
                formNuevaMateria.style.opacity = '0';
                
                setTimeout(function() {
                    formNuevaMateria.style.transition = 'max-height 0.3s ease, opacity 0.3s ease';
                    formNuevaMateria.style.maxHeight = formNuevaMateria.scrollHeight + 'px';
                    formNuevaMateria.style.opacity = '1';
                }, 10);
            } else {
                // Ocultar el formulario con animación
                formNuevaMateria.style.maxHeight = '0';
                formNuevaMateria.style.opacity = '0';
                
                setTimeout(function() {
                    formNuevaMateria.style.display = 'none';
                }, 300);
            }
        });
    });
</script>
@endsection
