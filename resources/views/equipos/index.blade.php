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
        <a href="{{ route('equipos.crear') }}" class="btn btn-success me-2">Añadir nuevos equipos</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    {{-- Mostrar mensaje de éxito si lo hay --}}
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) alert.style.display = 'none';
            }, 15000);
        </script>
    @endif

    {{-- Mostrar lotes de equipos agrupados por modelo --}}
    @forelse($equiposPorModelo as $modelo => $equipos)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">
                        <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="text-decoration-none text-dark">
                            {{ $modelo }}
                        </a>
                    </h5>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="btn btn-outline-primary btn-sm">
                        Ver equipos ({{ count($equipos) }})
                    </a>

                    <!-- Botón que lanza el modal -->
                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ Str::slug($modelo) }}">
                        ❌
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="modalEliminar{{ Str::slug($modelo) }}" tabindex="-1" aria-labelledby="modalLabel{{ Str::slug($modelo) }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalLabel{{ Str::slug($modelo) }}">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar <strong>todos los equipos</strong> del modelo <strong>{{ $modelo }}</strong>?
                        Esta acción no se puede deshacer.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('equipos.eliminarPorModelo', $modelo) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
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
