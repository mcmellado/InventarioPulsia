<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EQUIPOS PULSIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .serialnumbers-list {
            display: none;
        }
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
    </style>
</head>

    <div class="mb-3">
        <span class="badge bg-info"> Puesto: {{ Auth::user()->puesto }} </span>

    </div>

<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">📋 Lotes de equipos</h1>

        <div>
            <a href="{{ route('equipos.crear') }}" class="btn btn-success me-2">Añadir nuevos equipos</a>

   
            <a href="{{ route('users.create') }}" class="btn btn-primary me-2">Crear nuevo usuario</a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    {{-- Input buscador --}}
    <div class="mb-3">
        <input type="search" id="buscarModeloSerial" class="form-control" placeholder="Buscar por modelo o número de serie..." aria-label="Buscar por modelo o número de serie" autocomplete="off">
    </div>

    {{-- Contenedor para resultados de búsqueda por serialnumber --}}
    <div id="resultadosBusqueda" class="list-group"></div>

    {{-- Mostrar mensaje de éxito si lo hay --}}
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

    {{-- Mostrar lotes de equipos agrupados por modelo --}}
    <div id="listadoModelos">
    @forelse($equiposPorModelo as $modelo => $equipos)
        <div class="card mb-3 shadow-sm modelo-card" data-modelo="{{ strtolower($modelo) }}">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">
                        <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="text-decoration-none text-dark">
                            {{ $modelo }}
                        </a>
                    </h5>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('equipos.porModelo', ['modelo' => $modelo]) }}" class="btn btn-outline-primary btn-sm">
                        Ver equipos ({{ count($equipos) }})
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

    <hr>

    <h3 class="text-primary mt-4 mb-3">Equipos por puesto</h3>

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

        // Contar equipos por puesto (agregamos 0 si no hay)
        $conteoPorPuesto = [];
        foreach($puestos as $puesto){
            $conteoPorPuesto[$puesto] = 0;
        }
        foreach($equiposPorModelo as $modelo => $equipos){
            foreach($equipos as $equipo){
                $nombrePuesto = strtolower($equipo->puestoActual->nombre ?? '');
                if(array_key_exists($nombrePuesto, $conteoPorPuesto)){
                    $conteoPorPuesto[$nombrePuesto]++;
                }
            }
        }
    @endphp

    <div class="d-flex flex-wrap gap-2">
        @foreach($puestos as $puesto)
            <a href="{{ route('puestos.porPuesto', $puesto) }}" class="btn btn-outline-success btn-sm text-capitalize">
                {{ $puesto }} ({{ $conteoPorPuesto[$puesto] ?? 0 }})
            </a>
        @endforeach
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
        // Mostrar listado de lotes y ocultar resultados
        listadoModelos.style.display = 'block';
        resultadosDiv.style.display = 'none';
        resultadosDiv.innerHTML = '';

        // Mostrar todas las tarjetas de modelo
        const modelos = Array.from(document.querySelectorAll('.modelo-card'));
        modelos.forEach(card => card.style.display = 'block');

        return;
    }

    // Ocultar listado de lotes para mostrar resultados
    listadoModelos.style.display = 'none';

    // Buscar por modelo (lote)
    const modelos = Array.from(document.querySelectorAll('.modelo-card'));
    const modelosFiltrados = modelos.filter(card => card.getAttribute('data-modelo').includes(filtro));

    // Buscar por serialnumber (equipos)
    let resultadosEquipos = [];
    modelos.forEach(card => {
        const serials = card.querySelectorAll('.serialnumber');
        serials.forEach(span => {
            const serialText = span.getAttribute('data-serial');
            if(serialText.includes(filtro)) {
                resultadosEquipos.push({
                    serial: span.textContent,
                    modelo: span.getAttribute('data-modelo'),
                    puesto: span.getAttribute('data-puesto'),
                    url: span.getAttribute('data-url')
                });
            }
        });
    });

    // Mostrar resultados por modelo si hay (como tarjetas)
    if(modelosFiltrados.length > 0 && resultadosEquipos.length === 0) {
        listadoModelos.style.display = 'block';
        resultadosDiv.style.display = 'none';
        modelos.forEach(card => {
            card.style.display = modelosFiltrados.includes(card) ? 'block' : 'none';
        });
        resultadosDiv.innerHTML = '';
    } else {
        // Mostrar resultados de equipos (serialnumbers)
        resultadosDiv.style.display = 'block';
        resultadosDiv.innerHTML = '';

        if(resultadosEquipos.length === 0) {
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
