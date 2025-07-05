<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos modelo {{ $modelo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Equipos modelo: {{ $modelo }}</h1>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar sesión</button>
            </form>
        </div>

        @if($equipos->isEmpty())
            <div class="alert alert-warning">No hay equipos registrados para este modelo.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Número de serie</th>
                            <th>Puesto actual</th>
                            <th>Fecha de ingreso</th>
                            <th>Acciones</th> {{-- Nueva columna para botón --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->numero_serie }}</td>
                                <td>{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
                                <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
                                <td>
                                    <a href="{{ route('movimientos.crear', $equipo->id) }}" class="btn btn-sm btn-warning">
                                        Mover equipo
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
