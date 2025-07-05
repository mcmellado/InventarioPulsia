<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos en {{ $puesto->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-primary">Equipos en puesto: {{ $puesto->nombre }}</h1>

        <p class="mb-3"><strong>Total de equipos: {{ count($equipos) }}</strong></p>

        @if($equipos->isEmpty())
            <div class="alert alert-warning">No hay equipos en este puesto.</div>
        @else
            <form method="POST" action="{{ route('movimientos.guardarMultiple') }}">
                @csrf

                <p>Selecciona uno o varios equipos y elige el puesto destino para moverlos.</p>

                <div class="mb-3 d-flex align-items-center gap-3">
                    <label for="puesto_destino_id" class="mb-0">Mover seleccionados a:</label>
                    <select name="puesto_destino_id" id="puesto_destino_id" class="form-select w-auto" required>
                        <option value="" disabled selected>Selecciona un puesto</option>
                        @foreach ($puestos as $puestoOption)
                            @if($puestoOption->id !== $puesto->id)
                                <option value="{{ $puestoOption->id }}">{{ $puestoOption->nombre }}</option>
                            @endif
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-warning" id="moverSeleccionadosBtn" disabled>
                        Mover seleccionados
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Número de Serie</th>
                                <th>Modelo</th>
                                <th>Fecha de Ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipos as $equipo)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="equipo-checkbox">
                                    </td>
                                    <td>{{ $equipo->numero_serie }}</td>
                                    <td>{{ $equipo->modelo }}</td>
                                    <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        @endif

        <a href="{{ route('equipos.index') }}" class="btn btn-secondary mt-4">Volver al inicio</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Seleccionar / Deseleccionar todos
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.equipo-checkbox');
            checkboxes.forEach(chk => chk.checked = this.checked);
            toggleMoverButton();
        });

        // Habilitar botón si hay al menos un checkbox seleccionado y un puesto elegido
        const checkboxes = document.querySelectorAll('.equipo-checkbox');
        const selectPuesto = document.getElementById('puesto_destino_id');
        const btnMover = document.getElementById('moverSeleccionadosBtn');

        checkboxes.forEach(chk => chk.addEventListener('change', toggleMoverButton));
        selectPuesto.addEventListener('change', toggleMoverButton);

        function toggleMoverButton() {
            const anyChecked = [...document.querySelectorAll('.equipo-checkbox')].some(cb => cb.checked);
            const puestoSelected = selectPuesto.value !== "";
            btnMover.disabled = !(anyChecked && puestoSelected);
        }
    </script>
</body>
</html>
