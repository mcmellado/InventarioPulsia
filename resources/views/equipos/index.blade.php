<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EQUIPOS PULSIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">📋 Lotes de equipos</h1>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar sesión</button>
            </form>
        </div>

        @forelse($equiposPorModelo as $modelo => $equipos)
            <div class="card mb-3 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="text-decoration-none text-dark">
                            {{ $modelo }}
                        </a>
                    </h5>
                    <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="btn btn-outline-primary btn-sm">
                        Ver equipos ({{ count($equipos) }})
                    </a>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center mt-4">No hay equipos registrados.</div>
        @endforelse

        <hr>

        <h3 class="text-primary mt-4 mb-3">Equipos por puesto</h3>

        @php
            $puestos = [
                "admisión",
                "auditoría",
                "desmontaje",
                "reparación",
                "pintura",
                "teclados",
                "montaje",
                "calidad",
                "logística",
                "venta"
            ];
        @endphp

        <div class="d-flex flex-wrap gap-2">
            @foreach($puestos as $puesto)
                <a href="{{ route('puestos.porPuesto', $puesto) }}" class="btn btn-outline-success btn-sm text-capitalize">
                    {{ $puesto }}
                </a>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
