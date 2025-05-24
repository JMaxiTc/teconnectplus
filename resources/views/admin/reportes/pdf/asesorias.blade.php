<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #4e73df;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .date {
            font-size: 12px;
            color: #999;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fc;
            border-radius: 5px;
        }
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #4e73df;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .summary-item {
            padding: 10px;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .summary-label {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #4e73df;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        .page-break {
            page-break-after: always;
        }
        .estado-pendiente {
            color: #f6c23e;
            font-weight: bold;
        }
        .estado-confirmada, .estado-proceso {
            color: #4e73df;
            font-weight: bold;
        }
        .estado-finalizada {
            color: #1cc88a;
            font-weight: bold;
        }
        .estado-cancelada {
            color: #e74a3b;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $titulo }}</div>
        <div class="subtitle">TeConnect+ - Sistema de Asesorías Académicas</div>
        <div class="date">Fecha de generación: {{ date('d/m/Y H:i', strtotime($fecha)) }}</div>
    </div>

    <div class="summary">
        <div class="summary-title">Resumen</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total de Asesorías</div>
                <div class="summary-value">{{ $totalAsesorias }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Finalizadas</div>
                <div class="summary-value">{{ $finalizadas }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Pendientes</div>
                <div class="summary-value">{{ $pendientes }}</div>
            </div>
        </div>
    </div>

    <table>
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
                <td class="estado-{{ strtolower($asesoria->estado) }}">{{ $asesoria->estado }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} TeConnect+ | Este documento es confidencial y contiene información para uso administrativo exclusivamente.
    </div>
</body>
</html>
