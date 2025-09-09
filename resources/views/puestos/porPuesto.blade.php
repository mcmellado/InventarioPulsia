<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Equipos en {{ $puesto->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>

                 .table td {
                vertical-align: middle;
                text-align: center;  
                font-size: 0.9rem;
            }

            .td-observacion {
                text-align: center;
            }
            .td-observacion input {
                margin: 0 auto; /* centra el input */
                display: block;
            }

             
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

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #edededff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset('index-pulsia-fondo.jpg') }}") no-repeat;
            background-size: cover;
            transform: scaleX(-1);
            opacity: 0.08;
            filter: brightness(200%) grayscale(100%);
            z-index: -1;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0 0 0;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;

            /* Fondo con imagen */
            background: url("{{ asset('index-pulsia-fondo.jpg') }}") no-repeat center center;
            background-size: cover;

            /* Oscurecer un poco para que se lean los textos */
            background-blend-mode: overlay;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .sidebar .menu {
            margin: 0;
            padding: 1.5rem 1rem 0 1rem;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .sidebar .logo img {
            max-width: 120px;
        }

        .sidebar h2 {
            font-size: 0.9rem;
            color: #FFD700;
            margin: 1.5rem 0 0.5rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .sidebar a {
            display: block;
            padding: 0.6rem 1rem;
            margin-bottom: 0.3rem;
            border-radius: 8px;
            text-decoration: none;
            color: #bbb;
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .sidebar .btn-danger {
            border-radius: 0;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #FFD700;
            color: #000;
            font-weight: 600;
        }

        .mensaje {
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f0f0f0;
            text-align: center;
            font-weight: 500;
        }

        /* Botón logout al fondo */
        .sidebar form {
            margin: 0;
            padding: 0;
        }

        .content {
            flex: 1;
            padding: 2rem;
            margin-left: 250px;
        }

        .content h1 {
            font-size: 1.8rem;
            color: #141414;
            font-weight: 700;
        }



        .btn-custom {
            background: #FFD700;
            border: none;
            color: #000;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-custom:hover {
            background: #e6c200;
            color: #000;
        }

        .tick {
            font-size: 1.3em;
            color: green;
            margin-left: 6px;
            user-select: none;
            display: none;
        }

        /* Tablas */
        .table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .table thead {
            background: #141414;
            color: #FFD700;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 215, 0, 0.08);
            transition: 0.2s;
        }

        /* Inputs */
        .form-control,
        .form-select {
            border-radius: 8px;
            box-shadow: none;
            border: 1px solid #ddd;
            font-size: 0.9rem;
        }

        .obs-input {
            max-width: 250px;
            font-size: 0.85rem;
        }

        @media (max-width: 576px) {
            .obs-input {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu">
                <div class="logo">
                    <img src="{{ asset('logo-pulsia.svg') }}" alt="Pulsia" class="logo-click" style="cursor:pointer">
                </div>
                <div class="logo">
                </div>
                <p class="mensaje">Bienvenido, <i style="color:#FFD700">{{ auth()->user()->name }}_</i></p>
                <hr>

                <h2>EQUIPOS POR PUESTO</h2>

                @foreach($puestos as $p)
                <a href="{{ route('puestos.porPuesto', $p->nombre) }}" class="{{ $p->id === $puesto->id ? 'active' : '' }}">
                    {{ ucfirst($p->nombre) }}
                </a>
                @endforeach
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
            </form>
        </div>

        <!-- Contenido -->
        <div class="content">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 gap-3">
                <h1>Equipos en puesto: {{ $puesto->nombre }}</h1>
                <div class="d-flex gap-2 flex-wrap">
                    @auth
                    @if(Auth::user()->puesto === 'admin')
                    <a href="{{ route('equipos.crear') }}" class="btn btn-custom">Añadir nuevos equipos</a>
                    <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>
                    @endif
                    @endauth
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

                    @if(Auth::user()->puesto === 'admin')
                    <button type="button" class="btn btn-danger" id="eliminarSeleccionadosBtn" disabled data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        Eliminar seleccionados
                    </button>
                    @endif
                </div>

                <div id="alertSuccess" class="alert alert-success d-none" role="alert"></div>
                <div id="alertError" class="alert alert-danger d-none" role="alert"></div>


                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th scope="col"><input type="checkbox" id="selectAll" aria-label="Seleccionar todos" /></th>
                                <th scope="col">Número de serie</th>
                                <th scope="col">Puesto actual</th>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Fecha de ingreso</th>
                                <th scope="col">Observación</th>
                                <th scope="col">Trazabilidad</th>
                                <th scope="col">Grado</th>
                                <th scope="col">Calidad</th>
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
                                <td>{{ $equipo->proveedor->nombre ?? 'N/A' }}</td>
                                <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
                                <td class="d-flex align-items-center gap-2">
                                    <input type="text"
                                        name="observaciones[{{ $equipo->id }}]"
                                        class="form-control form-control-sm obs-input"
                                        placeholder="Observaciones"
                                        value="{{ old('observaciones.' . $equipo->id, $equipo->ultimoMovimiento->observaciones ?? '') }}"
                                        data-equipo-id="{{ $equipo->id }}" />
                                    <span class="tick" title="Guardado" aria-hidden="true">✔️</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm btn-ver-trazabilidad" data-equipo-id="{{ $equipo->id }}">
                                        Historial
                                    </button>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm grado-select" data-id="{{ $equipo->id }}">
                                        <option value="A" {{ $equipo->grado === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $equipo->grado === 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ $equipo->grado === 'C' ? 'selected' : '' }}>C</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn-ver-calidad" data-equipo-id="{{ $equipo->id }}">
                                        Estado del equipo
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

        <!-- Modal para mostrar calidad -->
        <div class="modal fade" id="calidadModal" tabindex="-1" aria-labelledby="calidadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="calidadModalLabel">Información de calidad</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Marca el estado de calidad del equipo:</p>
                        <div class="d-flex flex-wrap gap-3">
                            <button type="button" class="btn btn-outline-secondary quality-btn" data-key="pantalla" data-status="false">
                                Pantalla <span class="ms-2 d-none">&#10003;</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary quality-btn" data-key="teclado" data-status="false">
                                Teclado <span class="ms-2 d-none">&#10003;</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary quality-btn" data-key="hardware" data-status="false">
                                Hardware <span class="ms-2 d-none">&#10003;</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary quality-btn" data-key="bateria" data-status="false">
                                Batería <span class="ms-2 d-none">&#10003;</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary quality-btn" data-key="pintura" data-status="false">
                                Pintura <span class="ms-2 d-none">&#10003;</span>
                            </button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const calidadModal = new bootstrap.Modal(document.getElementById('calidadModal'));
                            const qualityButtons = document.querySelectorAll('.btn-ver-calidad');
                            const storagePrefix = 'calidadEstado_';

                            let currentEquipoId = null;

                            function cargarEstado(equipoId) {
                                const estadoStr = localStorage.getItem(storagePrefix + equipoId);
                                const estado = estadoStr ? JSON.parse(estadoStr) : {};

                                document.querySelectorAll('.quality-btn').forEach(btn => {
                                    const key = btn.getAttribute('data-key');
                                    const isOk = estado[key] === true;

                                    btn.setAttribute('data-status', isOk ? 'true' : 'false');
                                    btn.classList.toggle('btn-success', isOk);
                                    btn.classList.toggle('btn-outline-secondary', !isOk);
                                    btn.querySelector('span').classList.toggle('d-none', !isOk);
                                });
                            }

                            function guardarEstado(equipoId) {
                                const estado = {};
                                document.querySelectorAll('.quality-btn').forEach(btn => {
                                    const key = btn.getAttribute('data-key');
                                    estado[key] = btn.getAttribute('data-status') === 'true';
                                });
                                localStorage.setItem(storagePrefix + equipoId, JSON.stringify(estado));
                            }

                            // Clicks en los botones de calidad
                            document.querySelectorAll('.quality-btn').forEach(btn => {
                                btn.addEventListener('click', () => {
                                    const isOk = btn.getAttribute('data-status') === 'true';
                                    btn.setAttribute('data-status', isOk ? 'false' : 'true');
                                    btn.classList.toggle('btn-success', !isOk);
                                    btn.classList.toggle('btn-outline-secondary', isOk);
                                    btn.querySelector('span').classList.toggle('d-none', isOk);

                                    if (currentEquipoId) {
                                        guardarEstado(currentEquipoId);
                                    }
                                });
                            });

                            // Abrir modal con equipo específico
                            qualityButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    currentEquipoId = button.dataset.equipoId;
                                    cargarEstado(currentEquipoId);
                                    calidadModal.show();
                                });
                            });
                        });
                    </script>


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

                                if (btnEliminar) {
                                    btnEliminar.disabled = !anyChecked;
                                }
                            }

                            // Seleccionar todos
                            selectAll.addEventListener('change', function() {
                                checkboxes.forEach(chk => chk.checked = this.checked);
                                toggleActionButtons();
                            });

                            // Toggle individual
                            checkboxes.forEach(chk => {
                                chk.addEventListener('change', () => {
                                    if (!chk.checked) selectAll.checked = false;
                                    else if ([...checkboxes].every(cb => cb.checked)) selectAll.checked = true;
                                    toggleActionButtons();
                                });
                            });

                            selectPuesto.addEventListener('change', toggleActionButtons);
                            toggleActionButtons();

                            // === Mover equipos ===
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

                                    // Remover filas movidas
                                    checkboxes.forEach(chk => {
                                        if (chk.checked) {
                                            chk.closest('tr').remove();
                                        }
                                    });

                                    selectAll.checked = false;
                                    selectPuesto.value = "";
                                    toggleActionButtons();

                                    alertSuccess.textContent = "Equipos movidos correctamente.";
                                    alertSuccess.classList.remove('d-none');
                                    setTimeout(() => alertSuccess.classList.add('d-none'), 4000);
                                } catch (err) {
                                    console.error(err);
                                    alertError.textContent = "Error al guardar o mover los equipos.";
                                    alertError.classList.remove('d-none');
                                }
                            });

                            // === Eliminar equipos ===
                            if (btnEliminar) {
                                const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

                                btnEliminar.addEventListener('click', () => {
                                    confirmDeleteModal.show();
                                });

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
                                            headers: {
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': token
                                            },
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
                            }

                            // === Ver trazabilidad ===
                            const trazabilidadModal = new bootstrap.Modal(document.getElementById('trazabilidadModal'));
                            const trazabilidadContent = document.getElementById('trazabilidadContent');

                            document.querySelectorAll('.btn-ver-trazabilidad').forEach(button => {
                                button.addEventListener('click', async () => {
                                    const equipoId = button.dataset.equipoId;
                                    trazabilidadContent.innerHTML = '<p class="text-center">Cargando...</p>';
                                    trazabilidadModal.show();

                                    try {
                                        const response = await fetch(`/equipos/${equipoId}/trazabilidad`, {
                                            headers: {
                                                'Accept': 'application/json'
                                            }
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
                    <script>
                        document.querySelector('.logo .logo-click')
                            .addEventListener('click', () => window.location.href = "{{ route('equipos.index') }}");

                            document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.grado-select').forEach(select => {
        select.addEventListener('change', async () => {
            const id = select.dataset.id;
            const grado = select.value;

            try {
                const response = await fetch(`/equipos/${id}/grado`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ grado })
                });

                if (!response.ok) throw new Error("Error al actualizar el grado");

                const data = await response.json();
                console.log("Grado actualizado:", data.grado);

            } catch (err) {
                alert("No se pudo actualizar el grado");
                select.value = select.getAttribute("data-prev") || "A";
            }
        });
    });
});


                    </script>


</body>