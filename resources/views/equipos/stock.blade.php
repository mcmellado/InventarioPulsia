<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Equipos en Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body { margin:0; padding:0; font-family:'Plus Jakarta Sans',sans-serif; background:#edededff; min-height:100vh; overflow-x:hidden; }
    body::before { content:""; position:fixed; top:0; left:0; width:100%; height:100%; background:url("{{ asset('index-pulsia-fondo.jpg') }}") no-repeat; background-size:cover; transform:scaleX(-1); opacity:0.08; filter:brightness(200%) grayscale(100%); z-index:-1; }
    .table { background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
    .table thead { background:#141414; color:#FFD700; text-transform:uppercase; font-size:0.9rem; }
    .table th, .table td { vertical-align:middle; font-size:0.9rem; }
    .table-hover tbody tr:hover { background:rgba(255,215,0,0.08); transition:0.2s; }
    .badge-grade-A { background:#28a745; color:#fff; }
    .badge-grade-B { background: #007bff; color: #fff; }
    .badge-grade-C { background:#dc3545; color:#fff; }
</style>

<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Equipos en Stock</h1>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>
    </div>

    @if($equipos->isEmpty())
        <div class="alert alert-warning">No hay equipos disponibles en stock.</div>
    @else

        {{-- Resumen global por grado --}}
        @php
            $conteoGrados = $equipos->groupBy('grado')->map->count();
        @endphp
        <div class="mb-4">
            <h5 class="fw-bold">Resumen global por grado:</h5>
            <ul class="list-inline">
                <li class="list-inline-item badge badge-grade-A">Grado A: {{ $conteoGrados['A'] ?? 0 }}</li>
                <li class="list-inline-item badge badge-grade-B">Grado B: {{ $conteoGrados['B'] ?? 0 }}</li>
                <li class="list-inline-item badge badge-grade-C">Grado C: {{ $conteoGrados['C'] ?? 0 }}</li>
            </ul>
        </div>

        <div class="accordion" id="accordionStock">
            @php
                $equiposPorModelo = $equipos->groupBy('modelo');
            @endphp

            @foreach($equiposPorModelo as $modelo => $equiposModelo)
                @php
                    // Conteo de grados por modelo
                    $gradosModelo = $equiposModelo->groupBy('grado')->map->count();
                @endphp

                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="heading-{{ Str::slug($modelo) }}">
                        <button class="accordion-button collapsed d-flex align-items-center gap-2" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ Str::slug($modelo) }}"
                            aria-expanded="false"
                            aria-controls="collapse-{{ Str::slug($modelo) }}">
                            <img src="{{ asset('hp-2.svg') }}" alt="{{ $modelo }}" style="width:24px;height:24px;">
                            <span class="fw-bold">{{ $modelo }} ({{ $equiposModelo->count() }} equipos)</span>

                            {{-- Resumen por grado al lado del modelo --}}
                            <span class="ms-3">
                                <span class="badge badge-grade-A">A: {{ $gradosModelo['A'] ?? 0 }}</span>
                                <span class="badge badge-grade-B">B: {{ $gradosModelo['B'] ?? 0 }}</span>
                                <span class="badge badge-grade-C">C: {{ $gradosModelo['C'] ?? 0 }}</span>
                            </span>
                        </button>
                    </h2>

                    <div id="collapse-{{ Str::slug($modelo) }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ Str::slug($modelo) }}">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Número de serie</th>
                                            <th>Puesto actual</th>
                                            <th>Proveedor</th>
                                            <th>Fecha de ingreso</th>
                                            <th>Grado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equiposModelo as $equipo)
                                        <tr>
                                            <td>{{ $equipo->numero_serie }}</td>
                                            <td>{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
                                            <td>{{ $equipo->proveedor->nombre ?? 'N/A' }}</td>
                                            <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
                                            <td>
                                                @if($equipo->grado === 'A')
                                                    <span class="badge badge-grade-A">{{ $equipo->grado }}</span>
                                                @elseif($equipo->grado === 'B')
                                                    <span class="badge badge-grade-B">{{ $equipo->grado }}</span>
                                                @else
                                                    <span class="badge badge-grade-C">{{ $equipo->grado }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
