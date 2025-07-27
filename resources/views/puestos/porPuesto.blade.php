<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Equipos en {{ $puesto->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .tick {
            font-size: 1.3em;
            color: green;
            margin-left: 6px;
            user-select: none;
            display: none;
        }
        .obs-input {
            max-width: 250px;
        }
        @media (max-width: 576px) {
            .obs-input {
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
        <h1 class="text-primary mb-0">Equipos en puesto: {{ $puesto->nombre }}</h1>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('equipos.crear') }}" class="btn btn-success">Añadir nuevos equipos</a>
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>
        </div>
    </div>

    <p class="mb-3 fw-semibold">Total de equipos: {{ count($equipos) }}</p>

    @if($equipos->isEmpty())
        <div class="alert alert-warning">No hay equipos en este puesto.</div>
    @else
        <form id="formMoverEquipos" method="POST" action="{{ route('movimientos.guardarMultiple') }}">
            @csrf

            <p class="mb-3">Selecciona uno o varios equipos, escribe una observación (opcional) y elige el puesto destino para moverlos.</p>

            <div class="mb-4 d-flex flex-wrap align-items-center gap-3">
                <label for="puesto_destino_id" class="mb-0 fw-semibold">Mover seleccionados a:</label>
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

                <button type="button" class="btn btn-danger" id="eliminarSeleccionadosBtn" disabled data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    Eliminar seleccionados
                </button>
            </div>

            <div id="alertSuccess" class="alert alert-success d-none" role="alert"></div>
            <div id="alertError" class="alert alert-danger d-none" role="alert"></div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-dark">
    <tr>
        <th scope="col"><input type="checkbox" id="selectAll" aria-label="Seleccionar todos" /></th>
        <th scope="col">Número de serie</th>
        <th scope="col">Puesto actual</th>
        <th scope="col">Proveedor</th> <!-- Nueva columna -->
        <th scope="col">Fecha de ingreso</th>
        <th scope="col">Observación</th>
        <th scope="col">Trazabilidad</th> 
    </tr>
</thead>
<tbody>
@foreach($equipos as $equipo)
    <tr data-id="{{ $equipo->id }}">
        <td>
            <input type="checkbox" name="equipos[]" value="{{ $equipo->id }}" class="equipo-checkbox" aria-label="Seleccionar equipo {{ $equipo->numero_serie }}" />
        </td>
        <td>{{ $equipo->numero_serie }}</td>
        <td class="puesto-actual">{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
        <td>{{ $equipo->proveedor->nombre ?? 'N/A' }}</td> <!-- Aquí mostramos el proveedor -->
        <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
        <td class="d-flex align-items-center gap-2">
            <input type="text"
                   name="observaciones[{{ $equipo->id }}]"
                   class="form-control form-control-sm obs-input"
                   placeholder="Escribe una observación"
                   value="{{ old('observaciones.' . $equipo->id, $equipo->ultimoMovimiento->observaciones ?? '') }}"
                   data-equipo-id="{{ $equipo->id }}" />
            <span class="tick" title="Guardado" aria-hidden="true">✔️</span>
        </td>
        <td>
            <button type="button" class="btn btn-info btn-sm btn-ver-trazabilidad" data-equipo-id="{{ $equipo->id }}">
                Historial
            </button>
        </td>
    </tr>
@endforeach
</tbody>
                </table>
            </div>
        </form>
    @endif
</div>

<!-- Modal confirmación eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar los equipos seleccionados? Esta acción no se puede deshacer.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para mostrar trazabilidad -->
<div class="modal fade" id="trazabilidadModal" tabindex="-1" aria-labelledby="trazabilidadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="trazabilidadModalLabel">Trazabilidad del equipo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="trazabilidadContent">
            <p class="text-center">Cargando...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formMoverEquipos');
    const checkboxes = document.querySelectorAll('.equipo-checkbox');
    const selectPuesto = document.getElementById('puesto_destino_id');
    const btnMover = document.getElementById('moverSeleccionadosBtn');
    const btnEliminar = document.getElementById('eliminarSeleccionadosBtn');
    const selectAll = document.getElementById('selectAll');
    const alertSuccess = document.getElementById('alertSuccess');
    const alertError = document.getElementById('alertError');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function toggleActionButtons() {
        const anyChecked = [...checkboxes].some(cb => cb.checked);
        const puestoSelected = selectPuesto.value !== "";
        btnMover.disabled = !(anyChecked && puestoSelected);
        btnEliminar.disabled = !anyChecked;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(chk => chk.checked = this.checked);
        toggleActionButtons();
    });

    checkboxes.forEach(chk => {
        chk.addEventListener('change', () => {
            if (!chk.checked) selectAll.checked = false;
            else if ([...checkboxes].every(cb => cb.checked)) selectAll.checked = true;
            toggleActionButtons();
        });
    });

    selectPuesto.addEventListener('change', toggleActionButtons);

    toggleActionButtons();

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        alertSuccess.classList.add('d-none');
        alertError.classList.add('d-none');

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
            toggleActionButtons();

            alertSuccess.textContent = "Equipos movidos y observaciones guardadas correctamente.";
            alertSuccess.classList.remove('d-none');
            setTimeout(() => alertSuccess.classList.add('d-none'), 4000);
        } catch (err) {
            console.error(err);
            alertError.textContent = "Error al guardar o mover los equipos.";
            alertError.classList.remove('d-none');
        }
    });

    // Botón eliminar: muestra modal confirmación
                        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    confirmDeleteBtn.addEventListener('click', async () => {
        confirmDeleteBtn.disabled = true;
        alertSuccess.classList.add('d-none');
        alertError.classList.add('d-none');

        const formData = new FormData();
        formData.append('_token', token);
        document.querySelectorAll('.equipo-checkbox:checked').forEach(chk => {
            formData.append('equipos[]', chk.value);
        });

        try {
            const response = await fetch('{{ route("equipos.eliminarMultiple") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                body: formData
            });

            if (!response.ok) throw new Error('Error al eliminar');

            // Remover filas eliminadas
            document.querySelectorAll('.equipo-checkbox:checked').forEach(chk => {
                chk.closest('tr').remove();
            });
            
            toggleActionButtons();

            alertSuccess.textContent = "Equipos eliminados correctamente.";
            alertSuccess.classList.remove('d-none');
            setTimeout(() => alertSuccess.classList.add('d-none'), 4000);

            confirmDeleteModal.hide();
        } catch (error) {
            alertError.textContent = "Error al eliminar los equipos.";
            alertError.classList.remove('d-none');
            setTimeout(() => alertError.classList.add('d-none'), 4000);
        } finally {
            confirmDeleteBtn.disabled = false;
        }
    });

  // NUEVO: Manejar botón Ver trazabilidad
    const trazabilidadModal = new bootstrap.Modal(document.getElementById('trazabilidadModal'));
    const trazabilidadContent = document.getElementById('trazabilidadContent');

    document.querySelectorAll('.btn-ver-trazabilidad').forEach(button => {
        button.addEventListener('click', async () => {
            const equipoId = button.dataset.equipoId;
            trazabilidadContent.innerHTML = '<p class="text-center">Cargando...</p>';
            trazabilidadModal.show();

            try {
                const response = await fetch(`/equipos/${equipoId}/trazabilidad`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Error al obtener la trazabilidad');

                const movimientos = await response.json();

                if (movimientos.length === 0) {
                    trazabilidadContent.innerHTML = '<p class="text-center">No hay movimientos registrados para este equipo.</p>';
                    return;
                }

                // Crear tabla con la trazabilidad
                let html = `<table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Puesto origen</th>
                            <th>Puesto destino</th>
                            <th>Usuario</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>`;

                    movimientos.forEach(mov => {
                        html += `<tr>
                            <td>${mov.fecha}</td>
                            <td>${mov.puesto_origen}</td>
                            <td>${mov.puesto_destino}</td>
                            <td>${mov.usuario}</td>
                            <td>${mov.observaciones}</td>
                        </tr>`;
                    });


                html += `</tbody></table>`;
                trazabilidadContent.innerHTML = html;

            } catch (error) {
                trazabilidadContent.innerHTML = `<p class="text-danger text-center">Error al cargar la trazabilidad.</p>`;
            }
        });
    });
});
</script>

</body>
