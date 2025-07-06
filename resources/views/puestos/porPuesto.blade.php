<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Equipos en {{ $puesto->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
        <h1 class="text-primary">Equipos en puesto: {{ $puesto->nombre }}</h1>

        <p class="mb-3"><strong>Total de equipos: {{ count($equipos) }}</strong></p>

        @if($equipos->isEmpty())
            <div class="alert alert-warning">No hay equipos en este puesto.</div>
        @else
            <form id="formMoverEquipos" method="POST" action="{{ route('movimientos.guardarMultiple') }}">
                @csrf

                <p>Selecciona uno o varios equipos, escribe una observación (opcional) y elige el puesto destino para moverlos.</p>

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

                <div id="alertSuccess" class="alert alert-success d-none" role="alert"></div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Número de Serie</th>
                                <th>Modelo</th>
                                <th>Fecha de Ingreso</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipos as $equipo)
                                <tr data-id="{{ $equipo->id }}">
                                    <td>
                                        <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="equipo-checkbox" />
                                    </td>
                                    <td>{{ $equipo->numero_serie }}</td>
                                    <td>{{ $equipo->modelo }}</td>
                                    <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'N/A' }}</td>
                                    <td class="d-flex align-items-center gap-2">
                                        <input type="text"
                                               name="observaciones[{{ $equipo->id }}]"
                                               class="form-control form-control-sm obs-input"
                                               placeholder="Escribe una observación"
                                               value="{{ old('observaciones.' . $equipo->id, $equipo->ultima_observacion ?? '') }}"
                                               data-equipo-id="{{ $equipo->id }}" />
                                        <span class="tick" title="Guardado">✔️</span>
                                    </td>
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
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formMoverEquipos');
            const checkboxes = document.querySelectorAll('.equipo-checkbox');
            const selectPuesto = document.getElementById('puesto_destino_id');
            const btnMover = document.getElementById('moverSeleccionadosBtn');
            const selectAll = document.getElementById('selectAll');
            const alertSuccess = document.getElementById('alertSuccess');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function toggleMoverButton() {
                const anyChecked = [...checkboxes].some(cb => cb.checked);
                const puestoSelected = selectPuesto.value !== "";
                btnMover.disabled = !(anyChecked && puestoSelected);
            }

            selectAll.addEventListener('change', function () {
                checkboxes.forEach(chk => chk.checked = this.checked);
                toggleMoverButton();
            });

            checkboxes.forEach(chk => {
                chk.addEventListener('change', () => {
                    if (!chk.checked) selectAll.checked = false;
                    toggleMoverButton();
                });
            });

            selectPuesto.addEventListener('change', toggleMoverButton);

            toggleMoverButton();

            // Enviar formulario para mover equipos y guardar observaciones
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                alertSuccess.classList.add('d-none');

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: formData
                    });

                    if (!response.ok) throw new Error("Error al mover los equipos");

                    const data = await response.json();

                    // Remover filas de los equipos que se movieron
                    checkboxes.forEach(chk => {
                        if (chk.checked) {
                            chk.closest('tr').remove();
                        }
                    });

                    selectAll.checked = false;
                    selectPuesto.value = "";
                    toggleMoverButton();

                    alertSuccess.textContent = "Equipos movidos y observaciones guardadas correctamente.";
                    alertSuccess.classList.remove('d-none');
                    setTimeout(() => alertSuccess.classList.add('d-none'), 4000);

                } catch (err) {
                    console.error(err);
                    alert("Error al guardar o mover los equipos.");
                }
            });

            // Guardar observaciones automáticamente al salir del input
            document.querySelectorAll('.obs-input').forEach(input => {
                let timeoutId;

                input.addEventListener('input', () => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        guardarObservacion(input);
                    }, 800); // espera 800 ms 
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
