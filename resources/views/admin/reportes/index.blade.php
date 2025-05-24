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
    .header-section {
        background: linear-gradient(135deg, #1a5276, #2980b9, #3498db);
        color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .header-icon {
        background-color: white;
        color: #2980b9;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .header-content h1 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .header-content p {
        font-size: 14px;
        margin-bottom: 0;
        opacity: 0.9;
    }
    .btn-export {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
    }
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        background-color: #c82333;
    }
</style>
@endsection

@section('content')
{{-- 
    Sistema de Reportes TeConnect+
    - Todos los gráficos y estadísticas ahora usan datos reales de la base de datos
    - Se han eliminado los datos de ejemplo/inventados que se usaban anteriormente
    - Los datos de tendencias mensuales se basan en fechas reales de registros y asesorías
    - Las estadísticas de registro de usuarios se calculan usando el campo fecha_creacion
--}}
<div class="container py-4">    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4 page-header">
        <div>
            <h1 class="h3 mb-0">Panel de Reportes</h1>
            <p class="mb-0">Visualiza estadísticas y genera reportes del sistema TeConnect+</p>
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
                                Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Asesorías</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAsesorias }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                Materias</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMaterias }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                                Tasa Finalización</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalAsesorias > 0 ? round(($asesoriasFinalizadas / $totalAsesorias) * 100) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Tipos de Usuarios -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 card-stats">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución de Usuarios</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userTypesChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.reportes.usuarios') }}" class="btn btn-primary btn-sm">
                            Ver reporte completo de usuarios
                        </a>
                        <a href="{{ route('admin.reportes.pdf', 'usuarios') }}" class="btn btn-outline-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Exportar a PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 card-stats">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Estado de Asesorías</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="asesoriaStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.reportes.asesorias') }}" class="btn btn-success btn-sm">
                            Ver reporte completo de asesorías
                        </a>
                        <a href="{{ route('admin.reportes.pdf', 'asesorias') }}" class="btn btn-outline-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Exportar a PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4 card-stats">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Resumen de Materias</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="materiasChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.reportes.materias') }}" class="btn btn-info btn-sm">
                            Ver reporte completo de materias
                        </a>
                        <a href="{{ route('admin.reportes.pdf', 'materias') }}" class="btn btn-outline-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Exportar a PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Tendencias -->
    <div class="row mb-4">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencias Mensuales</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloques de Reportes -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reporte de Asesorías por Usuario</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Asesorías Realizadas</th>
                                    <th>Promedio de Calificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asesoriasPorUsuario as $usuario)
                                <tr>
                                    <td>{{ $usuario->nombre }}</td>
                                    <td>{{ $usuario->tipo }}</td>
                                    <td>{{ $usuario->total_asesorias }}</td>
                                    <td>{{ $usuario->promedio_calificacion }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reporte de Asesorías por Materia</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Asesorías Realizadas</th>
                                    <th>Promedio de Calificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asesoriasPorMateria as $materia)
                                <tr>
                                    <td>{{ $materia->nombre }}</td>
                                    <td>{{ $materia->total_asesorias }}</td>
                                    <td>{{ $materia->promedio_calificacion }}</td>
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
    // Gráfico de distribución de usuarios
    const userTypesChart = new Chart(
        document.getElementById('userTypesChart'),
        {
            type: 'doughnut',
            data: {
                labels: ['Estudiantes', 'Asesores', 'Administradores'],
                datasets: [{
                    data: [{{ $totalEstudiantes }}, {{ $totalAsesores }}, {{ $totalAdmins }}],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        }
    );

    // Gráfico de estado de asesorías
    const asesoriaStatusChart = new Chart(
        document.getElementById('asesoriaStatusChart'),
        {
            type: 'pie',
            data: {
                labels: ['Finalizadas', 'Pendientes', 'Activas', 'Canceladas'],
                datasets: [{
                    data: [
                        {{ $asesoriasFinalizadas }}, 
                        {{ $asesoriasPendientes }}, 
                        {{ $asesoriasActivas }}, 
                        {{ $asesoriasCanceladas }}
                    ],
                    backgroundColor: ['#1cc88a', '#f6c23e', '#4e73df', '#e74a3b'],
                    hoverBackgroundColor: ['#17a673', '#dda20a', '#2e59d9', '#be2617'],
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
    );    // Gráfico de tendencia mensual (datos reales)
    const asesoriasChartData = {
        labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Asesorías por mes',
            data: {!! json_encode($asesoriasPorMes) !!},
            fill: false,
            borderColor: '#4e73df',
            tension: 0.1
        }]
    };
    
    const usuariosChartData = {
        labels: {!! json_encode($meses) !!},
        datasets: [{
            label: 'Usuarios por mes',
            data: {!! json_encode($usuariosPorMes) !!},
            fill: false,
            borderColor: '#1cc88a',
            tension: 0.1
        }]
    };    // Gráfico representativo de materias (con datos reales)
    @php
    $materiasMasPopulares = Illuminate\Support\Facades\DB::table('asesoria')
        ->join('materia', 'asesoria.fk_id_materia', '=', 'materia.id_materia')
        ->select('materia.nombre')
        ->selectRaw('count(*) as total')
        ->groupBy('materia.nombre')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->pluck('total', 'nombre')
        ->toArray();
    @endphp
    
    const materiasMasPopulares = @json($materiasMasPopulares);
    
    const materiasLabels = Object.keys(materiasMasPopulares);
    const materiasData = Object.values(materiasMasPopulares);
    
    const materiasChart = new Chart(
        document.getElementById('materiasChart'),
        {
            type: 'bar',
            data: {
                labels: materiasLabels,
                datasets: [{
                    label: 'Asesorías por materia',
                    data: materiasData,
                    backgroundColor: '#36b9cc',
                    hoverBackgroundColor: '#2c9faf',
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    );

    // Gráfico de tendencias mensuales
    const trendChart = new Chart(
        document.getElementById('trendChart'),
        {
            type: 'line',
            data: {
                labels: {!! json_encode($meses) !!},
                datasets: [
                    {
                        label: 'Asesorías por mes',
                        data: {!! json_encode($asesoriasPorMes) !!},
                        borderColor: '#4e73df',
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Usuarios por mes',
                        data: {!! json_encode($usuariosPorMes) !!},
                        borderColor: '#1cc88a',
                        tension: 0.1,
                        fill: false
                    }
                ]
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
