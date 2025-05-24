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
            <h1 class="h3 mb-0">Reporte de Materias</h1>
            <p class="mb-0">Estadísticas y análisis de las materias del sistema TeConnect+</p>
        </div>
        <div>
            <a href="{{ route('admin.reportes.pdf', 'materias') }}" class="btn btn-danger btn-export">
                <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
            </a>
        </div>
    </div>
    
    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Materias</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($materias) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Materia + Popular</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if(count($materiasConMasAsesorias) > 0)
                                    {{ $materiasConMasAsesorias[0]->nombre }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 card-stats">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Promedio Asesores/Materia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $total = 0;
                                    foreach($materiasConMasAsesores as $materia) {
                                        $total += $materia->total_asesores;
                                    }
                                    $promedio = count($materias) > 0 ? round($total / count($materias), 1) : 0;
                                @endphp
                                {{ $promedio }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos de Materias -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Materias Más Populares (Asesorías)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topMateriasAsesoriasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Materias con Más Asesores</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topMateriasAsesoresChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Materias -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Materias</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="materiasTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Asesores</th>
                                    <th>Asesorías</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materias as $materia)
                                <tr>
                                    <td>{{ $materia->id_materia }}</td>
                                    <td>{{ $materia->nombre }}</td>
                                    <td>{{ $materia->codigo }}</td>
                                    <td>{{ $materia->descripcion }}</td>
                                    <td>
                                        @php
                                            $asesores = $materiasConMasAsesores->firstWhere('nombre', $materia->nombre);
                                            echo $asesores ? $asesores->total_asesores : 0;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            $asesorias = $materiasConMasAsesorias->firstWhere('nombre', $materia->nombre);
                                            echo $asesorias ? $asesorias->total_asesorias : 0;
                                        @endphp
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
    $('#materiasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });

    // Gráfico de materias por asesorías
    const materiasAsesoriasLabels = [];
    const materiasAsesoriasData = [];
    
    @foreach($materiasConMasAsesorias as $item)
        materiasAsesoriasLabels.push('{{ $item->nombre }}');
        materiasAsesoriasData.push({{ $item->total_asesorias }});
    @endforeach

    const topMateriasAsesoriasChart = new Chart(
        document.getElementById('topMateriasAsesoriasChart'),
        {
            type: 'bar',
            data: {
                labels: materiasAsesoriasLabels,
                datasets: [{
                    label: 'Asesorías Solicitadas',
                    data: materiasAsesoriasData,
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

    // Gráfico de materias por asesores
    const materiasAsesoresLabels = [];
    const materiasAsesoresData = [];
    
    @foreach($materiasConMasAsesores as $item)
        materiasAsesoresLabels.push('{{ $item->nombre }}');
        materiasAsesoresData.push({{ $item->total_asesores }});
    @endforeach

    const topMateriasAsesoresChart = new Chart(
        document.getElementById('topMateriasAsesoresChart'),
        {
            type: 'bar',
            data: {
                labels: materiasAsesoresLabels,
                datasets: [{
                    label: 'Asesores Asignados',
                    data: materiasAsesoresData,
                    backgroundColor: '#1cc88a',
                    hoverBackgroundColor: '#17a673',
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
