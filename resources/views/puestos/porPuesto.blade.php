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
            <form id="formMoverEquipos" method="POST" action="{{ route('movimientos.guardarMultiple') }}">
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
                <div id="alertSuccess" class="alert alert-success d-none" role="alert">
                    Equipos movidos correctamente.
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
                                <tr data-id="{{ $equipo->id }}">
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
        const form = document.getElementById('formMoverEquipos');
        const checkboxes = document.querySelectorAll('.equipo-checkbox');
        const selectPuesto = document.getElementById('puesto_destino_id');
        const btnMover = document.getElementById('moverSeleccionadosBtn');
        const selectAll = document.getElementById('selectAll');
        const alertBox = document.getElementById('alertSuccess');

        function toggleMoverButton() {
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            const puestoSelected = selectPuesto.value !== "";
            btnMover.disabled = !(anyChecked && puestoSelected);
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(chk => chk.checked = this.checked);
            toggleMoverButton();
        });

        checkboxes.forEach(chk => chk.addEventListener('change', () => {
            if (!chk.checked) selectAll.checked = false;
            toggleMoverButton();
        }));

        selectPuesto.addEventListener('change', toggleMoverButton);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (!response.ok) {
                    alert("Error al mover los equipos.");
                    return;
                }

                const data = await response.json();

                // Quitar selección y actualizar visualmente
                checkboxes.forEach(chk => {
                    if (chk.checked) {
                        const row = chk.closest('tr');
                        row.remove(); // Opcional: eliminar fila al mover
                        chk.checked = false;
                    }
                });

                selectAll.checked = false;
                selectPuesto.value = "";
                toggleMoverButton();

                // Mostrar alerta
                alertBox.classList.remove('d-none');
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 3000);

            } catch (error) {
                console.error(error);
                alert("Error de red.");
            }
        });
    </script>
</body>
</html>
