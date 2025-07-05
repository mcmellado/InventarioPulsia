<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Movimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-primary mb-4">Mover equipo: {{ $equipo->modelo }} - {{ $equipo->numero_serie }}</h1>

    <form method="POST" action="{{ route('movimientos.guardar', $equipo->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Puesto actual</label>
            <input type="text" class="form-control" value="{{ $equipo->puestoActual->nombre }}" disabled>
        </div>

        <div class="mb-3">
            <label for="puesto_destino_id" class="form-label">Nuevo puesto</label>
            <select name="puesto_destino_id" id="puesto_destino_id" class="form-select" required>
                <option value="">-- Selecciona un puesto --</option>
                @foreach($puestos as $puesto)
                    @if($puesto->id !== $equipo->puesto_actual_id)
                        <option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar movimiento</button>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
