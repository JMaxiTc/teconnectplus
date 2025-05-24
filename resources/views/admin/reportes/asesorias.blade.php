@extends('components.layout')

@section('styles')
<style>
    .card-stats {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-stats:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 2rem;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .status-badge {
        padding: 6px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-pendiente {
        background-color: #f6c23e;
        color: #fff;
    }
    .status-confirmada {
        background-color: #4e73df;
        color: #fff;
    }
    .status-proceso {
        background-color: #36b9cc;
        color: #fff;
    }
    .status-finalizada {
        background-color: #1cc88a;
        color: #fff;
    }
    .status-cancelada {
        background-color: #e74a3b;
        color: #fff;
    }
    .page-header h1 {
        font-weight: 600;
        color: #2c3e50;
    }
    .page-header p {
        font-size: 1rem;
        color: #6c757d;
    }
    .btn-export {
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container py-4">    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4 page-header">
        <div>
            <h1 class="h3 mb-0">Reporte de Asesorías</h1>
            <p class="mb-0">Estadísticas y análisis de las asesorías del sistema TeConnect+</p>
        </div>
        <div>
            <a href="{{ route('admin.reportes.pdf', 'asesorias') }}" class="btn btn-danger btn-export">
                <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
            </a>
        </div>
    </div>
    
    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Asesorías</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($asesorias) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Finalizadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asesorias->where('estado', 'FINALIZADA')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Proceso</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asesorias->whereIn('estado', ['CONFIRMADA', 'PROCESO'])->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Canceladas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asesorias->where('estado', 'CANCELADA')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Asesorías -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución por Estado</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="asesoriaStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Materias Más Solicitadas</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topMateriasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia de Asesorías por Mes</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="asesoriasTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Asesorías -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Asesorías</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="asesoriasTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tema</th>
                                    <th>Fecha</th>
                                    <th>Duración</th>
                                    <th>Estudiante</th>
                                    <th>Asesor</th>
                                    <th>Materia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asesorias as $asesoria)
                                <tr>
                                    <td>{{ $asesoria->id_asesoria }}</td>
                                    <td>{{ $asesoria->tema }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($asesoria->fecha)) }}</td>
                                    <td>{{ $asesoria->duracion }} min</td>
                                    <td>{{ $asesoria->estudiante->nombre }} {{ $asesoria->estudiante->apellido }}</td>
                                    <td>{{ $asesoria->asesor->nombre }} {{ $asesoria->asesor->apellido }}</td>
                                    <td>{{ $asesoria->materia->nombre }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($asesoria->estado) }}">
                                            {{ $asesoria->estado }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DataTable inicialización
    $('#asesoriasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        order: [[2, 'desc']] // Ordenar por fecha (columna 2) de forma descendente
    });

    // Gráfico de estado de asesorías
    const estadosData = [];
    const estadosLabels = [];
    
    @foreach($asesoriasPorEstado as $item)
        estadosLabels.push('{{ $item->estado }}');
        estadosData.push({{ $item->total }});
    @endforeach

    const asesoriaStatusChart = new Chart(
        document.getElementById('asesoriaStatusChart'),
        {
            type: 'pie',
            data: {
                labels: estadosLabels,
                datasets: [{
                    data: estadosData,
                    backgroundColor: ['#1cc88a', '#4e73df', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#2e59d9', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );

    // Gráfico de materias más solicitadas
    const materiasLabels = [];
    const materiasData = [];
    
    @foreach($materiasMasSolicitadas as $item)
        materiasLabels.push('{{ $item->nombre }}');
        materiasData.push({{ $item->total }});
    @endforeach

    const topMateriasChart = new Chart(
        document.getElementById('topMateriasChart'),
        {
            type: 'bar',
            data: {
                labels: materiasLabels,
                datasets: [{
                    label: 'Asesorías Solicitadas',
                    data: materiasData,
                    backgroundColor: '#4e73df',
                    hoverBackgroundColor: '#2e59d9',
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    );

    // Gráfico de tendencia de asesorías por mes
    const asesoriasTrendChart = new Chart(
        document.getElementById('asesoriasTrendChart'),
        {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Asesorías Realizadas',
                    data: {{ json_encode($asesoriasPorMes) }},
                    fill: false,
                    borderColor: '#4e73df',
                    tension: 0.1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        }
    );
});
</script>
@endsection
