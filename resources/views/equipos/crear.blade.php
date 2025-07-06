<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir varios equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Añadir varios equipos</h1>

    <form action="{{ route('equipos.guardarMultiple') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="numeros_serie" class="form-label">Números de serie (uno por línea)</label>
            <textarea class="form-control" id="numeros_serie" name="numeros_serie" rows="8" placeholder="Escribe o pega aquí los números de serie, uno por línea" required></textarea>
        </div>

        <div class="mb-3">
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" class="form-control" id="modelo" name="modelo" required>
        </div>

        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
        </div>

        <div class="mb-3">
            <label for="puesto_actual_id" class="form-label">Puesto</label>
            <select class="form-select" id="puesto_actual_id" name="puesto_actual_id" required>
                <option value="" disabled selected>Selecciona un puesto</option>
                @foreach($puestos as $puesto)
                    <option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar equipos</button>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
