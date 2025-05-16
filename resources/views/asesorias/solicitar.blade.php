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

<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
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
                        @csrf                        <!-- Layout mejorado con distribución horizontal -->
                        <div class="row">
                            <!-- Columna de selección de datos -->
                            <div class="col-lg-4">
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
                            <div class="col-lg-8">
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            Programación de Asesoría
                                        </h6>
                                    </div>
                                    <div class="card-body pb-2">
                                        <!-- Contenedor principal calendario-horarios -->
                                        <div class="row">
                                            <!-- Calendario a la izquierda -->
                                            <div class="col-md-6">
                                                <div class="calendar-container border rounded p-3 bg-white mb-3">
                                                    <div id="calendario"></div>
                                                    
                                                    <input type="hidden" name="fecha" id="fechaInput">
                                                    <input type="hidden" name="hora" id="horaInput">
                                                    <input type="hidden" name="duracion" id="duracionInput" value="60">
                                                </div>
                                            </div>
                                            
                                            <!-- Horarios a la derecha -->
                                            <div class="col-md-6">
                                                <div class="bg-light border rounded p-3 h-100">
                                                    <!-- Control de duración -->
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div>
                                                            <i class="fas fa-clock text-primary me-2"></i>
                                                            <span class="fw-bold">Horarios Disponibles</span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2 text-muted small">Duración:</span>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <button type="button" class="btn btn-outline-primary active" id="duracion1h">1 hora</button>
                                                                <button type="button" class="btn btn-outline-primary" id="duracion2h">2 horas</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Contenedor de horarios -->
                                                    <div class="time-selector overflow-auto" id="selectorHora" style="max-height: 320px;">
                                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
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
    // Configuración del calendario
    calendario = flatpickr("#calendario", {
        inline: true,
        locale: 'es',
        minDate: 'today',
        dateFormat: 'Y-m-d',
        disable: [
            function(date) {
                // Deshabilitar domingos (0) y sábados (6)
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],
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
    
    // Mostrar indicador de carga
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
        
        // Preparar contenedor para los horarios
        selectorHora.innerHTML = '';
        const contenedorScroll = document.createElement('div');
        contenedorScroll.className = 'time-slots-container';
        selectorHora.appendChild(contenedorScroll);
        
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
        
        // Crear encabezados y botones
        if (horariosMañana.length > 0) {
            const headerMañana = document.createElement('div');
            headerMañana.className = 'time-header mb-2 w-100';
            headerMañana.innerHTML = '<span class="badge bg-light text-primary">Mañana</span>';
            contenedorScroll.appendChild(headerMañana);
            
            const contenedorHorariosMañana = document.createElement('div');
            contenedorHorariosMañana.className = 'time-slots mb-3';
            contenedorScroll.appendChild(contenedorHorariosMañana);
            
            agregarBotonesHorario(horariosMañana, contenedorHorariosMañana);
        }
        
        if (horariosTarde.length > 0) {
            const headerTarde = document.createElement('div');
            headerTarde.className = 'time-header mb-2 w-100';
            headerTarde.innerHTML = '<span class="badge bg-light text-primary">Tarde</span>';
            contenedorScroll.appendChild(headerTarde);
            
            const contenedorHorariosTarde = document.createElement('div');
            contenedorHorariosTarde.className = 'time-slots';
            contenedorScroll.appendChild(contenedorHorariosTarde);
            
            agregarBotonesHorario(horariosTarde, contenedorHorariosTarde);
        }
    }, 800);
}

function agregarBotonesHorario(horas, container) {
    horas.forEach(hora => {
        const botonHora = document.createElement('button');
        botonHora.type = 'button';
        botonHora.className = 'btn btn-outline-primary hora-btn';
        
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
            
            botonHora.innerHTML = `<span>${horaInicioFormateada}</span> <i class="fas fa-arrow-right mx-1 small"></i> <span>${horaFinFormateada}</span>`;
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
            
            botonHora.innerHTML = `<span>${horaInicioFormateada}</span> <i class="fas fa-arrow-right mx-1 small"></i> <span>${horaFinFormateada}</span>`;
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
    }    /* Estilos generales */
    .card {
        overflow: hidden;
        transition: all 0.3s ease;
        border-radius: 12px;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    /* Estilos para el calendario */
    .flatpickr-calendar {
        width: 100% !important;
        max-width: 100%;
        box-shadow: none !important;
        margin: 0 auto;
        border-radius: 10px;
        border: none;
    }
    
    .flatpickr-day {
        border-radius: 50%;
        margin: 3px;
    }
    
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #1a73e8 !important;
        border-color: #1a73e8 !important;
        box-shadow: 0 3px 6px rgba(26, 115, 232, 0.2);
        font-weight: bold;
    }
    
    .flatpickr-day:hover {
        background: #e8f0fe !important;
    }
    
    .flatpickr-day.today {
        border-color: #1a73e8;
        color: #1a73e8;
        font-weight: bold;
    }
    
    .flatpickr-months .flatpickr-month {
        background-color: #1a73e8;
        color: white;
        fill: white;
        border-radius: 10px 10px 0 0;
        padding-top: 5px;
    }
    
    .flatpickr-current-month, .flatpickr-monthDropdown-months {
        color: white;
        font-weight: 600;
    }
    
    .flatpickr-weekdays {
        background-color: #e8f0fe;
    }
    
    .flatpickr-weekday {
        font-weight: 600;
        color: #1a73e8;
    }
    
    .calendar-container {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }
    
    /* Estilos para los horarios */
    .time-slots-container {
        padding-right: 5px;
    }
    
    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
        width: 100%;
    }
    
    .time-slots .btn {
        width: 100%;
        text-align: center;
        white-space: nowrap;
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 6px 8px;
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
    
    /* Estilos para el selector de duración */
    .btn-group-sm .btn {
        font-size: 0.85rem;
        border-radius: 6px;
        padding: 4px 10px;
    }
    
    /* Estilos para los encabezados de tiempo */
    .time-header {
        display: flex;
        align-items: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    
    .time-header .badge {
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 30px;
        background-color: #e8f0fe !important;
        color: #1a73e8 !important;
        font-size: 0.85rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .time-header:before, .time-header:after {
        content: "";
        flex: 1;
        height: 1px;
        background-color: #e9ecef;
        margin: 0 10px;
    }
    
    /* Estilos para el resumen de selección */
    #resumenSeleccion {
        transition: all 0.3s ease;
    }
    
    #resumenSeleccion .rounded-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }
    
    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #selectorHora, #seleccionFecha {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Media queries para responsividad */
    @media (max-width: 767.98px) {
        .time-slots {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }
    
    /* Fixes para mejor visualización en pantallas pequeñas */
    @media (max-width: 991.98px) {
        .card-body {
            padding: 1rem;
        }
    }
</style>
`);

// Validar formulario antes de enviar
document.getElementById('formAsesoria').addEventListener('submit', function(event) {
    const fecha = document.getElementById('fechaInput').value;
    const hora = document.getElementById('horaInput').value;
    const asesor = document.getElementById('asesorSelect').value;
    const materia = document.getElementById('materiaSelect').value;
    const tema = document.querySelector('input[name="tema"]').value;
    
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
    }
});

// Mostrar modal de confirmación en lugar de envío directo
document.getElementById('formAsesoria').addEventListener('submit', function(event) {
    // Prevenir envío automático para mostrar el modal primero
    event.preventDefault();
    
    // Validar formulario antes de mostrar modal
    const fecha = document.getElementById('fechaInput').value;
    const hora = document.getElementById('horaInput').value;
    const asesor = document.getElementById('asesorSelect').value;
    const materiaId = document.getElementById('materiaSelect').value;
    const tema = document.querySelector('textarea[name="tema"]').value;
    
    let mensajeError = '';
    
    if (!materiaId) {
        mensajeError += '- Seleccione una materia<br>';
    }
    
    if (!asesor) {
        mensajeError += '- Seleccione un asesor<br>';
    }
    
    if (!tema || tema.trim() === '') {
        mensajeError += '- Indique el tema de la asesoría<br>';
    }
    
    if (!fecha) {
        mensajeError += '- Seleccione una fecha para la asesoría<br>';
    }
    
    if (!hora) {
        mensajeError += '- Seleccione un horario para la asesoría<br>';
    }
    
    if (mensajeError) {
        Swal.fire({
            title: '¡Campos requeridos!',
            html: '<div class="text-start">' + mensajeError + '</div>',
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#1a73e8'
        });
        return;
    }
    
    // Si pasa la validación, mostrar modal de confirmación
    const materiaText = document.getElementById('materiaSelect').options[document.getElementById('materiaSelect').selectedIndex].text;
    const asesorText = document.getElementById('asesorSelect').options[document.getElementById('asesorSelect').selectedIndex].text;
    const fechaTexto = document.getElementById('fechaSeleccionada').innerText;
    const horaTexto = document.getElementById('horaSeleccionada').innerText;
    const duracion = document.getElementById('duracionInput').value === '60' ? '1 hora' : '2 horas';
    
    // Usar SweetAlert2 para mostrar la confirmación
    Swal.fire({
        title: '<i class="fas fa-clipboard-check text-primary"></i> Confirmar Solicitud',
        width: 500,
        html: `
            <div class="modal-confirmation">
                <div class="confirmation-header mb-3 text-center">
                    <div class="mb-2">
                        <div class="icon-circle mx-auto">
                            <i class="fas fa-calendar-check fa-lg text-primary"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">Detalles de tu Asesoría</h6>
                    <p class="text-muted small mb-0">Por favor confirma que los siguientes datos son correctos</p>
                </div>
                
                <div class="detail-list py-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Materia</div>
                                    <div>${materiaText}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-user-tie text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Asesor</div>
                                    <div>${asesorText}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Fecha</div>
                                    <div>${fechaTexto}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-clock text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Horario</div>
                                    <div>${horaTexto}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-hourglass-half text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Duración</div>
                                    <div>${duracion}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="icon-container-sm me-3 mt-1">
                                    <i class="fas fa-edit text-primary"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-muted fw-bold">Tema de la asesoría</div>
                                    <div class="tema-text">${tema}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-msg mt-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        <div>Tu solicitud será enviada al asesor para confirmación.</div>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Confirmar',
        cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Editar',
        buttonsStyling: false,
        confirmButtonColor: '#1a73e8',
        cancelButtonColor: '#6c757d',
        focusConfirm: false,
        customClass: {
            popup: 'swal-wide',
            confirmButton: 'btn btn-primary btn-lg px-4',
            cancelButton: 'btn btn-outline-secondary btn-lg px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar mensaje de carga mientras se procesa
            Swal.fire({
                title: 'Enviando solicitud...',
                html: 'Por favor espera mientras procesamos tu solicitud',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar el formulario
            document.getElementById('formAsesoria').submit();
        }
    });
});
</script>

<!-- Estilo adicional para la modal -->
<style>
    .swal-wide {
        width: 500px !important;
        max-width: 95% !important;
    }
    
    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
        width: 100%;
    }
    
    .modal-confirmation .icon-circle {
        width: 64px;
        height: 64px;
        background-color: rgba(26, 115, 232, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .icon-container-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: rgba(26, 115, 232, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .tema-text {
        word-break: break-word;
        max-height: 60px;
        overflow-y: auto;
    }
    
    .detail-list {
        border-top: 1px solid rgba(0,0,0,0.05);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .info-msg {
        font-size: 12px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .swal-wide {
            width: 95% !important;
        }
    }
    
    .btn-lg {
        padding: 0.45rem 0.9rem;
        font-size: 0.95rem;
    }
</style>
@endsection
