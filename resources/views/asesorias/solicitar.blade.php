@extends('components.layout')

@section('content')
<!-- Agregar enlaces a Flatpickr CSS y JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<!-- SweetAlert2 para notificaciones atractivas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-11 mx-auto">
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
                        @csrf                        <!-- Layout mejorado con distribución horizontal -->                        <div class="row">
                            <!-- Columna de selección de datos -->
                            <div class="col-lg-3">
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Información Básica
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
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
                                        
                                        <div class="mb-3">
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
                                        
                                        <div class="mb-0">
                                            <label class="form-label d-flex align-items-center">
                                                <i class="fas fa-edit text-primary me-2"></i>
                                                <span>Tema de la asesoría</span>
                                            </label>
                                            <textarea name="tema" class="form-control border-0 bg-light shadow-sm" 
                                                placeholder="Describe brevemente el tema que deseas consultar" rows="3"></textarea>
                                            @error('tema')
                                                <div class="text-danger small mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Panel de información seleccionada -->
                                <div class="card shadow-sm border-0" id="resumenSeleccion">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-check-circle text-primary me-2"></i>
                                            Resumen de Selección
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="selected-date-time p-0" id="seleccionFecha" style="display: none;">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="rounded-circle bg-light p-2 me-3">
                                                    <i class="fas fa-calendar-check text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Fecha seleccionada</div>
                                                    <div class="fw-bold" id="fechaSeleccionada">No seleccionada</div>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center mb-0" id="horaSeleccionadaContainer" style="display: none;">
                                                <div class="rounded-circle bg-light p-2 me-3">
                                                    <i class="fas fa-clock text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Horario seleccionado</div>
                                                    <div class="fw-bold" id="horaSeleccionada"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center p-3" id="noSeleccion">
                                            <div class="text-muted mb-2">
                                                <i class="fas fa-calendar-alt fa-2x text-light"></i>
                                            </div>
                                            <p class="text-muted small mb-0">Seleccione fecha y hora para su asesoría</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna del calendario -->
                            <div class="col-lg-9">
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            Programación de Asesoría
                                        </h6>
                                    </div>
                                    <div class="card-body pb-4">
                                        <!-- Contenedor principal calendario-horarios -->
                                        <div class="row g-4">                                            <!-- Calendario a la izquierda -->                                            <div class="col-md-5">
                                                <div class="calendar-container border rounded p-3 p-lg-4 bg-white mb-3">
                                                    <div id="calendario" class="w-100 mx-auto"></div>
                                                    
                                                    <input type="hidden" name="fecha" id="fechaInput">
                                                    <input type="hidden" name="hora" id="horaInput">
                                                    <input type="hidden" name="duracion" id="duracionInput" value="60">
                                                </div>
                                            </div>
                                            
                                            <!-- Horarios a la derecha -->
                                            <div class="col-md-7">
                                                <div class="bg-light border rounded p-4 h-100">
                                                    <!-- Control de duración -->
                                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                                        <div>
                                                            <i class="fas fa-clock text-primary me-2"></i>
                                                            <span class="fw-bold">Horarios Disponibles</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2 text-muted">Duración:</span>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-outline-primary active" id="duracion1h">1 hora</button>
                                                                <button type="button" class="btn btn-outline-primary" id="duracion2h">2 horas</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Pestañas para mañana y tarde -->
                                                    <div id="horariosTabs" class="mb-3" style="display: none;">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item" role="presentation" id="tabMañanaContainer">
                                                                <button class="nav-link active" id="tab-mañana" data-bs-toggle="tab" data-bs-target="#panel-mañana" type="button" role="tab">
                                                                    <i class="fas fa-sun me-2 text-warning"></i>Mañana
                                                                    <span class="badge bg-primary rounded-pill ms-2" id="contadorMañana"></span>
                                                                </button>
                                                            </li>
                                                            <li class="nav-item" role="presentation" id="tabTardeContainer">
                                                                <button class="nav-link" id="tab-tarde" data-bs-toggle="tab" data-bs-target="#panel-tarde" type="button" role="tab">
                                                                    <i class="fas fa-moon me-2 text-primary"></i>Tarde
                                                                    <span class="badge bg-primary rounded-pill ms-2" id="contadorTarde"></span>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    
                                                    <!-- Contenedor de horarios -->
                                                    <div class="tab-content" style="min-height: 320px;">
                                                        <div class="tab-pane fade show active" id="panel-mañana" role="tabpanel">
                                                            <div class="horarios-container" id="horariosMañana" style="max-height: 300px; overflow-y: auto;">
                                                                <!-- Los horarios de mañana se cargarán aquí -->
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="panel-tarde" role="tabpanel">
                                                            <div class="horarios-container" id="horariosTarde" style="max-height: 300px; overflow-y: auto;">
                                                                <!-- Los horarios de tarde se cargarán aquí -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="time-selector" id="selectorHora">
                                                        <div class="d-flex align-items-center justify-content-center h-100 py-5 text-muted">
                                                            <div class="text-center">
                                                                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                                                <p class="mb-0">Seleccione una fecha para ver los horarios disponibles</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    </div>
                                    
                                    <!-- Mensajes de error para fecha y hora -->
                                    <div class="mt-3">
                                        @error('fecha')
                                            <div class="text-danger small mb-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        
                                        @error('hora')
                                            <div class="text-danger small">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción con diseño mejorado -->
                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light p-2 me-3">
                                                <i class="fas fa-info-circle text-primary"></i>
                                            </div>
                                            <div class="small text-muted">
                                                Las asesorías están sujetas a confirmación por parte del asesor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-md-end justify-content-center gap-3">
                                            <a href="/" class="btn btn-outline-secondary px-4">
                                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>Enviar solicitud</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

// Inicializar calendario Flatpickr
let calendario;
let asesorSeleccionado = null;
let fechaSeleccionada = null;
let duracionSeleccionada = 60; // 1 hora por defecto

document.addEventListener('DOMContentLoaded', function() {
    // Función para ajustar el tamaño del calendario al contenedor
    function ajustarCalendario() {
        const contenedor = document.querySelector('.calendar-container');
        const calendario = document.querySelector('.flatpickr-calendar');
        if (contenedor && calendario) {
            // Asegurar que el calendario se ajusta al contenedor
            calendario.style.width = (contenedor.clientWidth - 20) + 'px';
            calendario.style.maxWidth = '100%';
        }
    }
    
    // Configuración del calendario
    calendario = flatpickr("#calendario", {
        inline: true,
        locale: 'es',
        minDate: 'today',
        dateFormat: 'Y-m-d',
        static: true,
        position: "auto center",
        disableMobile: true,
        animate: false,
        showMonths: 1,
        fixedHeight: true,
        monthSelectorType: "static",
        disable: [
            function(date) {
                // Deshabilitar domingos (0) y sábados (6)
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],        onReady: function() {
            // Ajustar el calendario una vez que esté listo
            setTimeout(ajustarCalendario, 100);
            
            // Observar cambios en el tamaño de la ventana
            window.addEventListener('resize', ajustarCalendario);
        },
        onChange: function(selectedDates, dateStr) {
            fechaSeleccionada = dateStr;
            document.getElementById('fechaInput').value = dateStr;
            document.getElementById('fechaSeleccionada').innerText = formatearFecha(dateStr);
            document.getElementById('seleccionFecha').style.display = 'block';
            document.getElementById('noSeleccion').style.display = 'none';
            
            // Mostrar selector de horas si hay un asesor seleccionado
            if (asesorSeleccionado) {
                mostrarHorasDisponibles(asesorSeleccionado, dateStr);
            } else {
                // Si no hay asesor, mostrar mensaje para seleccionar uno primero
                const selectorHora = document.getElementById('selectorHora');
                selectorHora.innerHTML = `
                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fa-lg text-primary"></i>
                            <div>
                                <strong>Seleccione un asesor</strong>
                                <div class="small">Necesita seleccionar un asesor para ver sus horarios disponibles</div>
                            </div>
                        </div>
                    </div>`;
            }
        }
    });
    
    // Manejar cambios en la selección de asesor
    document.getElementById('asesorSelect').addEventListener('change', function() {
        asesorSeleccionado = this.value;
        if (fechaSeleccionada && asesorSeleccionado) {
            mostrarHorasDisponibles(asesorSeleccionado, fechaSeleccionada);
        }
    });
    
    // Manejar cambios en la duración (1 hora)
    document.getElementById('duracion1h').addEventListener('click', function() {
        document.getElementById('duracion1h').classList.add('active');
        document.getElementById('duracion2h').classList.remove('active');
        duracionSeleccionada = 60;
        document.getElementById('duracionInput').value = duracionSeleccionada;
        
        if (fechaSeleccionada && asesorSeleccionado) {
            mostrarHorasDisponibles(asesorSeleccionado, fechaSeleccionada);
        }
    });
    
    // Manejar cambios en la duración (2 horas)
    document.getElementById('duracion2h').addEventListener('click', function() {
        document.getElementById('duracion2h').classList.add('active');
        document.getElementById('duracion1h').classList.remove('active');
        duracionSeleccionada = 120;
        document.getElementById('duracionInput').value = duracionSeleccionada;
        
        if (fechaSeleccionada && asesorSeleccionado) {
            mostrarHorasDisponibles(asesorSeleccionado, fechaSeleccionada);
        }
    });
});

// Función para mostrar horas disponibles con diseño mejorado
function mostrarHorasDisponibles(asesorId, fecha) {
    const selectorHora = document.getElementById('selectorHora');
    const horariosTabs = document.getElementById('horariosTabs');
    
    // Mostrar indicador de carga
    selectorHora.style.display = 'block';
    horariosTabs.style.display = 'none';
    
    selectorHora.innerHTML = `
        <div class="d-flex justify-content-center align-items-center h-100 py-4">
            <div class="spinner-border text-primary me-3" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <span>Consultando horarios disponibles...</span>
        </div>`;
    
    // Simulamos una petición al servidor con datos consistentes basados en la fecha y el asesor
    setTimeout(() => {
        // Generar horas disponibles basadas en la fecha y el asesor
        const fecha_obj = new Date(fecha);
        const dia = fecha_obj.getDay(); // 0 (domingo) a 6 (sábado)
        const semilla = fecha_obj.getDate() + parseInt(asesorId); // Crear pseudo-aleatoriedad consistente
        
        const horasDisponibles = generarHorasDisponibles(dia, semilla);
        
        if (horasDisponibles.length === 0) {
            selectorHora.style.display = 'block';
            horariosTabs.style.display = 'none';
            selectorHora.innerHTML = `
                <div class="alert alert-warning mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                        <div>
                            <strong>Sin disponibilidad</strong>
                            <div>No hay horarios disponibles para esta fecha. Por favor, seleccione otra fecha.</div>
                        </div>
                    </div>
                </div>`;
            return;
        }
        
        // Para sesiones de 2 horas, eliminar los horarios que no tienen al menos 2 horas consecutivas disponibles
        let horasFiltradas = horasDisponibles;
        if (duracionSeleccionada === 120) {
            horasFiltradas = horasDisponibles.filter(hora => {
                const horaActual = parseInt(hora.split(':')[0]);
                const minutos = hora.split(':')[1] === '30' ? 30 : 0;
                
                // Verificar si hay otro horario disponible después de 1 hora
                const siguienteHora = `${(horaActual + 1).toString().padStart(2, '0')}:${minutos}`;
                return horasDisponibles.includes(siguienteHora);
            });
        }
        
        if (horasFiltradas.length === 0) {
            selectorHora.style.display = 'block';
            horariosTabs.style.display = 'none';
            selectorHora.innerHTML = `
                <div class="alert alert-warning mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                        <div>
                            <strong>Sin disponibilidad</strong>
                            <div>No hay bloques de ${duracionSeleccionada === 120 ? '2 horas' : '1 hora'} disponibles para esta fecha.</div>
                        </div>
                    </div>
                </div>`;
            return;
        }
        
        // Agrupar horarios por mañana y tarde
        const horariosMañana = [];
        const horariosTarde = [];
        
        horasFiltradas.forEach(hora => {
            const horaNum = parseInt(hora.split(':')[0]);
            if (horaNum < 12) {
                horariosMañana.push(hora);
            } else {
                horariosTarde.push(hora);
            }
        });
        
        // Ocultar el selector de horas y mostrar las pestañas
        selectorHora.style.display = 'none';
        horariosTabs.style.display = 'block';
        
        // Configurar los contadores y visibilidad de las pestañas
        const contadorMañana = document.getElementById('contadorMañana');
        const contadorTarde = document.getElementById('contadorTarde');
        const tabMañanaContainer = document.getElementById('tabMañanaContainer');
        const tabTardeContainer = document.getElementById('tabTardeContainer');
        
        // Actualizar contadores
        contadorMañana.textContent = horariosMañana.length;
        contadorTarde.textContent = horariosTarde.length;
        
        // Mostrar/ocultar pestañas según disponibilidad
        tabMañanaContainer.style.display = horariosMañana.length > 0 ? 'block' : 'none';
        tabTardeContainer.style.display = horariosTarde.length > 0 ? 'block' : 'none';
        
        // Si solo hay horarios en un periodo, seleccionar esa pestaña automáticamente
        if (horariosMañana.length > 0 && horariosTarde.length === 0) {
            document.getElementById('tab-mañana').classList.add('active');
            document.getElementById('panel-mañana').classList.add('show', 'active');
            document.getElementById('tab-tarde').classList.remove('active');
            document.getElementById('panel-tarde').classList.remove('show', 'active');
        } else if (horariosMañana.length === 0 && horariosTarde.length > 0) {
            document.getElementById('tab-tarde').classList.add('active');
            document.getElementById('panel-tarde').classList.add('show', 'active');
            document.getElementById('tab-mañana').classList.remove('active');
            document.getElementById('panel-mañana').classList.remove('show', 'active');
        } else {
            // Por defecto, seleccionar la pestaña de la mañana si hay horarios en ambos periodos
            document.getElementById('tab-mañana').classList.add('active');
            document.getElementById('panel-mañana').classList.add('show', 'active');
            document.getElementById('tab-tarde').classList.remove('active');
            document.getElementById('panel-tarde').classList.remove('show', 'active');
        }
        
        // Llenar los contenedores de horarios
        const contenedorHorariosMañana = document.getElementById('horariosMañana');
        const contenedorHorariosTarde = document.getElementById('horariosTarde');
        
        // Limpiar contenedores anteriores
        contenedorHorariosMañana.innerHTML = '';
        contenedorHorariosTarde.innerHTML = '';
        
        // Agregar clases para el estilo de la cuadrícula
        contenedorHorariosMañana.className = 'time-slots';
        contenedorHorariosTarde.className = 'time-slots';
        
        // Agregar horarios a cada panel
        if (horariosMañana.length > 0) {
            agregarBotonesHorario(horariosMañana, contenedorHorariosMañana);
        } else {
            contenedorHorariosMañana.innerHTML = `
                <div class="alert alert-info mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>No hay horarios disponibles por la mañana</div>
                    </div>
                </div>`;
        }
        
        if (horariosTarde.length > 0) {
            agregarBotonesHorario(horariosTarde, contenedorHorariosTarde);
        } else {
            contenedorHorariosTarde.innerHTML = `
                <div class="alert alert-info mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-3"></i>
                        <div>No hay horarios disponibles por la tarde</div>
                    </div>
                </div>`;
        }
    }, 800);
}

function agregarBotonesHorario(horas, container) {
    horas.forEach(hora => {
        const botonHora = document.createElement('button');
        botonHora.type = 'button';
        botonHora.className = 'btn btn-outline-primary hora-btn mb-1';
        
        if (duracionSeleccionada === 120) {
            // Para 2 horas, mostrar el rango completo
            const horaInicio = hora;
            const horaParts = hora.split(':');
            const horaNum = parseInt(horaParts[0]);
            const minutos = horaParts[1];
            
            const horaFinNum = horaNum + 2;
            const horaFin = `${horaFinNum.toString().padStart(2, '0')}:${minutos}`;
            
            // Formatear la hora para mostrar AM/PM
            const horaInicioFormateada = formatearHora(horaInicio);
            const horaFinFormateada = formatearHora(horaFin);
            
            botonHora.innerHTML = `<div class="d-flex flex-column">
                <div>${horaInicioFormateada}</div>
                <div><i class="fas fa-arrow-down text-secondary"></i></div>
                <div>${horaFinFormateada}</div>
            </div>`;
            botonHora.dataset.horaInicio = horaInicio;
            botonHora.dataset.horaFin = horaFin;
            botonHora.dataset.horaTexto = `${horaInicioFormateada} - ${horaFinFormateada}`;
        } else {
            // Para 1 hora, mostrar la hora de inicio y calcular la hora final
            const horaParts = hora.split(':');
            const horaNum = parseInt(horaParts[0]);
            const minutos = horaParts[1];
            
            const horaFinNum = horaNum + 1;
            const horaFin = `${horaFinNum.toString().padStart(2, '0')}:${minutos}`;
            
            // Formatear la hora para mostrar AM/PM
            const horaInicioFormateada = formatearHora(hora);
            const horaFinFormateada = formatearHora(horaFin);
            
            botonHora.innerHTML = `<div class="d-flex flex-column">
                <div>${horaInicioFormateada}</div>
                <div><i class="fas fa-arrow-down text-secondary"></i></div>
                <div>${horaFinFormateada}</div>
            </div>`;
            botonHora.dataset.horaInicio = hora;
            botonHora.dataset.horaFin = horaFin;
            botonHora.dataset.horaTexto = `${horaInicioFormateada} - ${horaFinFormateada}`;
        }
        
        botonHora.onclick = function() {
            // Desmarcar todas las horas
            document.querySelectorAll('.time-slots .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Marcar esta hora como seleccionada
            this.classList.add('active');
            
            // Guardar la hora seleccionada en el input hidden
            document.getElementById('horaInput').value = this.dataset.horaInicio;
            
            // Mostrar la hora seleccionada en el resumen
            document.getElementById('horaSeleccionada').textContent = this.dataset.horaTexto;
            document.getElementById('horaSeleccionadaContainer').style.display = 'flex';
            
            // Hacer scroll al principio para ver la selección completa
            window.scrollTo({ top: document.getElementById('resumenSeleccion').offsetTop - 20, behavior: 'smooth' });
        };
        
        container.appendChild(botonHora);
    });
}

// Función para generar horas disponibles de manera consistente basada en día y semilla
function generarHorasDisponibles(dia, semilla) {
    const horas = [];
    // Define horarios según el día (para hacerlo más realista)
    let horaInicio, horaFin;
    
    // Ajustamos las horas según el día de la semana
    switch(dia) {
        case 1: // Lunes
            horaInicio = 9; // 9:00 AM
            horaFin = 17;   // 5:00 PM
            break;
        case 2: // Martes
        case 4: // Jueves
            horaInicio = 8; // 8:00 AM
            horaFin = 18;   // 6:00 PM
            break;
        case 3: // Miércoles
            horaInicio = 9; // 9:00 AM
            horaFin = 19;   // 7:00 PM
            break;
        case 5: // Viernes
            horaInicio = 8; // 8:00 AM
            horaFin = 15;   // 3:00 PM
            break;
        default:
            horaInicio = 10; // 10:00 AM
            horaFin = 14;    // 2:00 PM
    }
    
    // Creamos un patrón de disponibilidad basado en la semilla para obtener resultados consistentes
    for (let hora = horaInicio; hora < horaFin; hora++) {
        // Usamos la semilla y la hora para determinar disponibilidad (70% de disponibilidad)
        const disponible1 = ((hora + semilla) % 10) < 7;
        if (disponible1) {
            horas.push(`${hora.toString().padStart(2, '0')}:00`);
        }
        
        // Media hora, menos disponibilidad (50%)
        const disponible2 = ((hora + semilla + 5) % 10) < 5;
        if (hora < horaFin - 1 && disponible2) {
            horas.push(`${hora.toString().padStart(2, '0')}:30`);
        }
    }
    
    return horas;
}

// Formatear hora para mostrar en formato 12h (AM/PM)
function formatearHora(hora24) {
    const [hora, minutos] = hora24.split(':');
    const horaNum = parseInt(hora);
    const periodo = horaNum >= 12 ? 'PM' : 'AM';
    const hora12 = horaNum > 12 ? horaNum - 12 : (horaNum === 0 ? 12 : horaNum);
    
    return `${hora12}:${minutos} ${periodo}`;
}

// Formatear la fecha para mostrarla al usuario
function formatearFecha(fechaStr) {
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', opciones);
}

// Agregar estilo para indicador de carga
document.head.insertAdjacentHTML('beforeend', `
<style>
    .loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
    }

    .loading::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 50 50'%3E%3Cpath fill='%231a73e8' d='M25,5A20.14,20.14,0,0,1,45,22.88a2.51,2.51,0,0,0,2.49,2.26h0A2.52,2.52,0,0,0,50,22.33a25.14,25.14,0,0,0-50,0,2.52,2.52,0,0,0,2.5,2.81h0A2.51,2.51,0,0,0,5,22.88,20.14,20.14,0,0,1,25,5Z'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 25 25' to='360 25 25' dur='0.6s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E") center / 30px no-repeat;
    }      /* Estilo para el calendario */
    .calendar-container {
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    
    #calendario {
        display: block;
        margin: 0 auto;
        width: 100% !important;
        max-width: 100%;
        overflow: hidden;
    }
    
    /* Ajustes para contener el calendario dentro de su contenedor */
    .flatpickr-calendar {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .flatpickr-months, .flatpickr-month, .flatpickr-weekdaycontainer, .flatpickr-days {
        width: 100% !important;
    }
    
    /* Estilos para la selección de horas */
    .time-slots-container {
        height: 100%;
        overflow-y: auto;
    }
    
    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        width: 100%;
        padding: 15px 5px;
    }
    
    .time-slots .btn {
        width: 100%;
        text-align: center;
        white-space: nowrap;
        font-size: 0.9rem;
        border-radius: 8px;
        padding: 8px 12px;
        border-width: 1px;
    }
    
    .time-slots .btn.active {
        background-color: #1a73e8;
        color: white;
        box-shadow: 0 3px 6px rgba(26, 115, 232, 0.2);
        font-weight: 600;
    }
    
    .time-slots .hora-btn {
        transition: all 0.2s ease;
    }
    
    .time-slots .hora-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .horarios-container {
        padding: 10px 5px;
    }
    
    /* Estilos para las pestañas de horarios */
    #horariosTabs {
        animation: fadeIn 0.3s ease;
        margin-bottom: 0.75rem !important;
    }
    
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        border: 1px solid transparent;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    
    .nav-tabs .nav-link.active {
        color: #1a73e8;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        border-color: #e9ecef #e9ecef #dee2e6;
        background-color: #f8f9fa;
    }
    
    .tab-content {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 0.25rem 0.25rem;
        padding: 15px;
        margin-bottom: 1rem;
    }
      /* Ajustar el tamaño de los botones para dispositivos móviles */
    @media (max-width: 767.98px) {
        .time-slots {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
            padding: 10px 0;
        }
        
        .time-slots .btn {
            font-size: 0.8rem;
            padding: 6px 8px;
        }
          .calendar-container {
            padding: 10px !important;
            margin-bottom: 20px;
        }
        
        #calendario .flatpickr-month {
            padding-top: 5px;
            padding-bottom: 5px;
        }
        
        .flatpickr-calendar {
            font-size: 14px;
        }
        
        .dayContainer {
            padding: 0 !important;
        }
        
        .flatpickr-day {
            max-width: 34px;
            height: 34px;
            line-height: 34px;
        }
    }
    
    /* Estilos para el panel de resumen */
    #resumenSeleccion {
        transition: all 0.3s ease;
    }
    
    #resumenSeleccion:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
      /* Personalización de Flatpickr */
    .flatpickr-day.selected {
        background: #1a73e8 !important;
        border-color: #1a73e8 !important;
    }
    
    .flatpickr-day.today {
        border-color: #1a73e8 !important;
    }
    
    .flatpickr-day.today:hover {
        background: #e6f0ff !important;
        color: #1a73e8;
    }
    
    .flatpickr-day:hover {
        background: #e6f0ff !important;
        border-color: #e6f0ff !important;
    }
    
    /* Ajustes adicionales para mantener el calendario dentro de su contenedor */
    .calendar-container .flatpickr-calendar {
        left: 0 !important;
        right: 0 !important;
        margin: 0 auto;
    }
    
    .flatpickr-innerContainer, .flatpickr-rContainer {
        width: 100% !important;
        max-width: 100%;
    }
    
    .flatpickr-days {
        width: 100% !important;
        min-width: unset !important;
    }
    
    .dayContainer {
        width: 100% !important;
        min-width: unset !important;
        max-width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
`);

// Validar formulario antes de enviar
document.getElementById('formAsesoria').addEventListener('submit', function(event) {
    const fecha = document.getElementById('fechaInput').value;
    const hora = document.getElementById('horaInput').value;
    const asesor = document.getElementById('asesorSelect').value;
    const materia = document.getElementById('materiaSelect').value;
    const tema = document.querySelector('textarea[name="tema"]').value;
    
    let mensajeError = '';
    
    if (!materia) {
        mensajeError += '- Seleccione una materia\n';
    }
    
    if (!asesor) {
        mensajeError += '- Seleccione un asesor\n';
    }
    
    if (!tema || tema.trim() === '') {
        mensajeError += '- Indique el tema de la asesoría\n';
    }
    
    if (!fecha) {
        mensajeError += '- Seleccione una fecha para la asesoría\n';
    }
    
    if (!hora) {
        mensajeError += '- Seleccione un horario para la asesoría\n';
    }
    
    if (mensajeError) {
        event.preventDefault();
        Swal.fire({
            title: '¡Campos requeridos!',
            html: '<div class="text-start">Por favor complete:<br>' + mensajeError.replace(/\n/g, '<br>') + '</div>',
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#1a73e8'
        });
    } else {
        // Si todos los campos están completos, mostrar un resumen de la solicitud
        event.preventDefault();
        const duracion = document.getElementById('duracionInput').value === '60' ? '1 hora' : '2 horas';
        const materiaTexto = document.getElementById('materiaSelect').options[document.getElementById('materiaSelect').selectedIndex].text;
        const asesorTexto = document.getElementById('asesorSelect').options[document.getElementById('asesorSelect').selectedIndex].text;
        const fechaTexto = document.getElementById('fechaSeleccionada').textContent;
        const horaTexto = document.getElementById('horaSeleccionada').textContent;
        
        Swal.fire({
            title: 'Confirmar solicitud',
            html: `
                <div class="text-start">
                    <p class="mb-1"><strong>Materia:</strong> ${materiaTexto}</p>
                    <p class="mb-1"><strong>Asesor:</strong> ${asesorTexto}</p>
                    <p class="mb-1"><strong>Fecha:</strong> ${fechaTexto}</p>
                    <p class="mb-1"><strong>Horario:</strong> ${horaTexto} (${duracion})</p>
                    <p class="mb-1"><strong>Tema:</strong> ${tema}</p>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#1a73e8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirmar solicitud',
            cancelButtonText: 'Revisar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar mensaje de procesando
                Swal.fire({
                    title: 'Enviando solicitud',
                    html: 'Por favor, espere un momento...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Enviar el formulario
                this.submit();
            }
        });
    }
});
</script>
@endsection
