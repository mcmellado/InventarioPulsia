<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos modelo {{ $modelo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .tick {
            font-size: 1.3em;
            color: green;
            margin-left: 6px;
            user-select: none;
            display: none;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Equipos modelo: {{ $modelo }}</h1>
        <a href="{{ route('equipos.crear') }}" class="btn btn-success me-2">Añadir nuevos equipos</a>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>
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
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($equipos as $equipo)
                        <tr data-id="{{ $equipo->id }}">
                            <td>
                                <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="equipo-checkbox">
                            </td>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td class="puesto-actual">{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
                            <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
                            <td class="d-flex align-items-center gap-2">
                                <input type="text"
                                       name="observaciones[{{ $equipo->id }}]"
                                       class="form-control form-control-sm obs-input"
                                       placeholder="Escribe una observación"
                                       value="{{ old('observaciones.' . $equipo->id, $equipo->ultimoMovimiento->observaciones ?? '') }}"
                                       data-equipo-id="{{ $equipo->id }}">
                                <span class="tick" title="Guardado">✔️</span>
                            </td>
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
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('formMoverEquipos');
        const checkboxes = document.querySelectorAll('.equipo-checkbox');
        const selectPuesto = document.getElementById('puesto_destino_id');
        const btnMover = document.getElementById('moverSeleccionadosBtn');
        const selectAll = document.getElementById('selectAll');
        const alertBox = document.getElementById('alertSuccess');
        const alertError = document.getElementById('alertError');
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function toggleMoverButton() {
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            const puestoSelected = selectPuesto.value !== "";
            btnMover.disabled = !(anyChecked && puestoSelected);
        }

        // Checkbox general
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(chk => {
                chk.checked = this.checked;
            });
            toggleMoverButton();
        });

        // Cambios individuales
        checkboxes.forEach(chk => {
            chk.addEventListener('change', () => {
                if (!chk.checked) selectAll.checked = false;
                toggleMoverButton();
            });
        });

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

                if (!response.ok) throw new Error('Error al enviar');

                const data = await response.json();

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

            } catch (err) {
                console.error(err);
                alertError.classList.remove('d-none');
                setTimeout(() => alertError.classList.add('d-none'), 4000);
            }
        });

        // Guardar observaciones automáticamente 
        document.querySelectorAll('.obs-input').forEach(input => {
            let timeoutId;

            input.addEventListener('input', () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    guardarObservacion(input);
                }, 800);
            });

            input.addEventListener('blur', () => {
                clearTimeout(timeoutId);
                guardarObservacion(input);
            });
        });

        async function guardarObservacion(input) {
            const equipoId = input.dataset.equipoId;
            const observacion = input.value.trim();
            const tick = input.nextElementSibling;

            if (input.dataset.lastValue === observacion) {
                return;
            }
            input.dataset.lastValue = observacion;

            try {
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('equipos[]', equipoId);
                formData.append(`observaciones[${equipoId}]`, observacion);

                const response = await fetch('{{ route("observaciones.guardarMultiple") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: formData
                });

                if (!response.ok) throw new Error('Error al guardar observación');

                tick.style.display = 'inline';
                setTimeout(() => {
                    tick.style.display = 'none';
                }, 1500);

            } catch (error) {
                console.error(error);
                alert('Error al guardar la observación.');
            }
        }
    });
</script>

</body>
</html>
