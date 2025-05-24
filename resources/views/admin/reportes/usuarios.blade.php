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
<div class="container py-4">    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4 page-header">
        <div>
            <h1 class="h3 mb-0">Reporte de Usuarios</h1>
            <p class="mb-0">Estadísticas y análisis de usuarios del sistema TeConnect+</p>
            
            {{-- Las estadísticas de crecimiento mensual ahora usan el campo fecha_creacion para mayor precisión --}}
        </div>
        <div>
            <a href="{{ route('admin.reportes.pdf', 'usuarios') }}" class="btn btn-danger btn-export">
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
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($usuarios) }}</div>
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
                                Estudiantes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usuarios->where('rol', 'ESTUDIANTE')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
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
                                Asesores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usuarios->where('rol', 'ASESOR')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usuarios->where('rol', 'ADMIN')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Usuarios -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución de Usuarios por Rol</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userRolesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estudiantes por Semestre</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="studentsBySemesterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Crecimiento de Usuarios por Mes</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Usuarios</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>Semestre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->id_usuario }}</td>
                                    <td>{{ $usuario->nombre }}</td>
                                    <td>{{ $usuario->apellido }}</td>
                                    <td>{{ $usuario->correo }}</td>
                                    <td>
                                        @if($usuario->rol == 'ESTUDIANTE')
                                            <span class="badge bg-success text-white">Estudiante</span>
                                        @elseif($usuario->rol == 'ASESOR')
                                            <span class="badge bg-info text-white">Asesor</span>
                                        @else
                                            <span class="badge bg-danger text-white">Admin</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->semestre }}</td>
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
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });

    // Gráfico de roles de usuarios
    const userRolesChart = new Chart(
        document.getElementById('userRolesChart'),
        {
            type: 'doughnut',
            data: {
                labels: ['Estudiantes', 'Asesores', 'Administradores'],
                datasets: [{
                    data: [
                        {{ $usuarios->where('rol', 'ESTUDIANTE')->count() }}, 
                        {{ $usuarios->where('rol', 'ASESOR')->count() }}, 
                        {{ $usuarios->where('rol', 'ADMIN')->count() }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#be2617'],
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

    // Gráfico de estudiantes por semestre
    const semestres = [];
    const estudiantesPorSemestre = [];
    
    @foreach($estudiantesPorSemestre as $item)
        semestres.push('Semestre {{ $item->semestre }}');
        estudiantesPorSemestre.push({{ $item->total }});
    @endforeach

    const studentsBySemesterChart = new Chart(
        document.getElementById('studentsBySemesterChart'),
        {
            type: 'bar',
            data: {
                labels: semestres,
                datasets: [{
                    label: 'Estudiantes por Semestre',
                    data: estudiantesPorSemestre,
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

    // Gráfico de crecimiento de usuarios por mes
    const userGrowthChart = new Chart(
        document.getElementById('userGrowthChart'),
        {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: 'Usuarios Registrados',
                    data: {{ json_encode($usuariosPorMes) }},
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
