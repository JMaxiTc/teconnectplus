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
    </style>
</head>
<body>
    <div class="header">        <div class="title">{{ $titulo }}</div>
        <div class="subtitle">TeConnect+ - Sistema de Asesorías Académicas</div>
        <div class="date">Fecha de generación: {{ date('d/m/Y H:i', strtotime($fecha)) }}</div>
        <!-- Las estadísticas de crecimiento mensual ahora usan el campo fecha_creacion para mayor precisión -->
    </div>

    <div class="summary">
        <div class="summary-title">Resumen</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total de Usuarios</div>
                <div class="summary-value">{{ $totalUsuarios }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Estudiantes</div>
                <div class="summary-value">{{ $totalEstudiantes }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Asesores</div>
                <div class="summary-value">{{ $totalAsesores }}</div>
            </div>
        </div>
    </div>

    <table>
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
                <td>{{ $usuario->rol }}</td>
                <td>{{ $usuario->semestre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} TeConnect+ | Este documento es confidencial y contiene información para uso administrativo exclusivamente.
    </div>
</body>
</html>
