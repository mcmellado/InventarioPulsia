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

    <p class="mb-3"><strong>Total de equipos: {{ count($equipos) }}</strong></p>

    @if($equipos->isEmpty())
        <div class="alert alert-warning">No hay equipos registrados para este modelo.</div>
    @else
        <form id="formMoverEquipos" method="POST" action="{{ route('movimientos.guardarMultiple') }}">
            @csrf

            <p>Selecciona uno o varios equipos y elige el puesto destino para moverlos.</p>

            <div class="mb-3 d-flex align-items-center gap-3">
                <label for="puesto_destino_id" class="mb-0">Mover seleccionados a:</label>
                <select name="puesto_destino_id" id="puesto_destino_id" class="form-select w-auto" required>
                    <option value="" disabled selected>Selecciona un puesto</option>
                    @foreach ($puestos as $puesto)
                        <option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-warning" id="moverSeleccionadosBtn" disabled>
                    Mover seleccionados
                </button>
            </div>

            <div id="alertSuccess" class="alert alert-success d-none" role="alert">
                Equipos movidos correctamente.
            </div>

            <div id="alertError" class="alert alert-danger d-none" role="alert">
                Ocurrió un error al mover los equipos.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Número de serie</th>
                            <th>Puesto actual</th>
                            <th>Fecha de ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($equipos as $equipo)
                        <tr data-id="{{ $equipo->id }}">
                            <td><input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="equipo-checkbox"></td>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td class="puesto-actual">{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
                            <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const form = document.getElementById('formMoverEquipos');
    const checkboxes = document.querySelectorAll('.equipo-checkbox');
    const selectPuesto = document.getElementById('puesto_destino_id');
    const btnMover = document.getElementById('moverSeleccionadosBtn');
    const selectAll = document.getElementById('selectAll');
    const alertBox = document.getElementById('alertSuccess');
    const alertError = document.getElementById('alertError');

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

        alertBox.classList.add('d-none');
        alertError.classList.add('d-none');

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Respuesta no OK');

            const data = await response.json();

            // Actualizar puesto actual en la tabla
            const puestoNombre = data.puesto_nombre;
            checkboxes.forEach(chk => {
                if (chk.checked) {
                    const row = chk.closest('tr');
                    row.querySelector('.puesto-actual').textContent = puestoNombre;
                    chk.checked = false;
                }
            });

            selectAll.checked = false;
            selectPuesto.value = "";
            toggleMoverButton();

            alertBox.classList.remove('d-none');
            setTimeout(() => alertBox.classList.add('d-none'), 4000);

        } catch (error) {
            console.error('Error de red o servidor:', error);
            alertError.classList.remove('d-none');
            setTimeout(() => alertError.classList.add('d-none'), 4000);
        }
    });
</script>

</body>
</html>
