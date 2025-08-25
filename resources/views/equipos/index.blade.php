<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>EQUIPOS PULSIA</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #c1c1c1ff;
            /* color base */
            position: relative;
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
            background: url("{{ asset('index-pulsia-fondo.jpg') }}") no-repeat center center;
            background-size: cover;
            transform: scaleX(-1);
            /* Invierte horizontalmente la imagen */
            opacity: 0.08;
            /* Blanco suave */
            filter: brightness(200%) grayscale(100%);
            /* aclara y blanquea */
            z-index: -1;
            /* lo manda al fondo */
        }


        /* Layout */
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

        /* Botón logout full width sin márgenes */
        .sidebar form .btn {
            width: 100%;
            border-radius: 0;
            margin: 0;
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

        .sidebar a:hover,
        .sidebar a.active {
            background: #FFD700;
            color: #000;
            font-weight: 600;
        }

        /* Botón logout al fondo */
        .sidebar form {
            margin-top: auto;
        }

        /* Contenido */
        .content {
            flex: 1;
            padding: 2rem;
            margin-left: 250px;
            /* espacio para el sidebar */
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

        /* Resultados búsqueda */
        #resultadosBusqueda {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        #resultadosBusqueda .resultado-item {
            cursor: pointer;
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }

        #resultadosBusqueda .resultado-item:hover {
            background-color: #f0f0f0;
        }

        .serialnumbers-list {
            display: none;
        }

        .mensaje {
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #f0f0f0;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu">
            <div class="logo">
                <img src="{{ asset('logo-pulsia.svg') }}" alt="Pulsia">
            </div>
            <p class="mensaje">Bienvenido, <i style="color:#FFD700">{{ auth()->user()->name }}_</i></p>
            <hr>


            <h2>Equipos por puesto</h2>
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

            @foreach($puestos as $puesto)
            <a href="{{ route('puestos.porPuesto', $puesto) }}">
                {{ ucfirst($puesto) }}
            </a>
            @endforeach

            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
            </form>
        </div>

        <!-- Contenido principal -->
        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📋 Lotes de equipos</h1>
                <a href="{{ route('equipos.crear') }}" class="btn btn-custom">Añadir equipos</a>
                <a href="{{ route('albaran.index') }}" class="btn btn-info me-2">Albarán</a>
                <a href="{{ route('equipos.stock') }}" class="btn btn-warning me-2">Stock</a>

            </div>

            {{-- Input buscador --}}
            <div class="mb-3">
                <input type="search" id="buscarModeloSerial" class="form-control" placeholder="Buscar por modelo o número de serie..." autocomplete="off">
            </div>

            {{-- Resultados búsqueda --}}
            <div id="resultadosBusqueda" class="list-group mb-3"></div>

            {{-- Mensaje de éxito --}}
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

            {{-- Listado de equipos por modelo --}}
            <div id="listadoModelos">
                @forelse($equiposPorModelo as $modelo => $equipos)
                <div class="card mb-3 shadow-sm modelo-card" data-modelo="{{ strtolower($modelo) }}">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="text-decoration-none text-dark">
                                {{ $modelo }}
                            </a>
                        </h5>

                        <div class="d-flex gap-2">
                            <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="btn btn-outline-primary btn-sm">
                                Ver ({{ count($equipos) }})
                            </a>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar{{ Str::slug($modelo) }}">
                                ❌
                            </button>
                        </div>

                        {{-- Lista oculta de serialnumbers para búsqueda --}}
                        <div class="serialnumbers-list">
                            @foreach($equipos as $equipo)
                            <span class="serialnumber"
                                data-serial="{{ strtolower($equipo->numero_serie) }}"
                                data-modelo="{{ strtolower($modelo) }}"
                                data-puesto="{{ strtolower($equipo->puestoActual->nombre ?? '') }}"
                                data-url="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}">
                                {{ $equipo->numero_serie }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Modal eliminación --}}
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const inputBuscar = document.getElementById('buscarModeloSerial');
        const resultadosDiv = document.getElementById('resultadosBusqueda');
        const listadoModelos = document.getElementById('listadoModelos');

        inputBuscar.addEventListener('input', function() {
            const filtro = this.value.trim().toLowerCase();

            if (filtro === '') {
                listadoModelos.style.display = 'block';
                resultadosDiv.style.display = 'none';
                resultadosDiv.innerHTML = '';
                const modelos = Array.from(document.querySelectorAll('.modelo-card'));
                modelos.forEach(card => card.style.display = 'block');
                return;
            }

            listadoModelos.style.display = 'none';

            const modelos = Array.from(document.querySelectorAll('.modelo-card'));
            const modelosFiltrados = modelos.filter(card => card.getAttribute('data-modelo').includes(filtro));

            let resultadosEquipos = [];
            modelos.forEach(card => {
                const serials = card.querySelectorAll('.serialnumber');
                serials.forEach(span => {
                    const serialText = span.getAttribute('data-serial');
                    if (serialText.includes(filtro)) {
                        resultadosEquipos.push({
                            serial: span.textContent,
                            modelo: span.getAttribute('data-modelo'),
                            puesto: span.getAttribute('data-puesto'),
                            url: span.getAttribute('data-url')
                        });
                    }
                });
            });

            if (modelosFiltrados.length > 0 && resultadosEquipos.length === 0) {
                listadoModelos.style.display = 'block';
                resultadosDiv.style.display = 'none';
                modelos.forEach(card => {
                    card.style.display = modelosFiltrados.includes(card) ? 'block' : 'none';
                });
                resultadosDiv.innerHTML = '';
            } else {
                resultadosDiv.style.display = 'block';
                resultadosDiv.innerHTML = '';

                if (resultadosEquipos.length === 0) {
                    resultadosDiv.innerHTML = '<div class="p-2 text-muted">No se encontraron resultados.</div>';
                } else {
                    resultadosEquipos.forEach(equipo => {
                        const modeloCapitalizado = equipo.modelo.charAt(0).toUpperCase() + equipo.modelo.slice(1);
                        const puestoCapitalizado = equipo.puesto ? equipo.puesto.charAt(0).toUpperCase() + equipo.puesto.slice(1) : 'N/A';

                        const item = document.createElement('a');
                        item.href = equipo.url;
                        item.className = 'list-group-item list-group-item-action resultado-item';
                        item.innerHTML = `<strong>Equipo:</strong> ${equipo.serial} <br> <small>Lote: ${modeloCapitalizado} | Puesto: ${puestoCapitalizado}</small>`;
                        resultadosDiv.appendChild(item);
                    });
                }
                modelos.forEach(card => card.style.display = 'none');
            }
        });
    </script>
</body>

</html>