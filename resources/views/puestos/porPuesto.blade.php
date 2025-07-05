<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos en {{ $puesto->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-primary mb-4">Equipos en puesto: {{ $puesto->nombre }}</h1>

        {{-- Contador de equipos --}}
        <div class="mb-3">
            <strong>Total de equipos: {{ count($equipos) }}</strong>
        </div>

        @if($equipos->isEmpty())
            <div class="alert alert-warning">No hay equipos en este puesto.</div>
        @else
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Número de Serie</th>
                        <th>Modelo</th>
                        <th>Fecha de Ingreso</th>
                        <th>Acciones</th> {{-- Nueva columna para botón --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td>{{ $equipo->modelo }}</td>
                            <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('movimientos.crear', $equipo->id) }}" class="btn btn-sm btn-warning">
                                    Mover equipo
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('equipos.index') }}" class="btn btn-secondary mt-4">Volver al inicio</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
