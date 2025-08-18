<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Equipos en Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Equipos en Stock</h1>
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver a la lista general</a>
        </div>

        @if($equipos->isEmpty())
        <div class="alert alert-warning">No hay equipos disponibles en stock.</div>
        @else
        <div class="accordion" id="accordionStock">
            @php
            // Agrupar equipos por modelo
            $equiposPorModelo = $equipos->groupBy('modelo');
            @endphp

            @foreach($equiposPorModelo as $modelo => $equiposModelo)
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="heading-{{ Str::slug($modelo) }}">
                    <button class="accordion-button collapsed d-flex align-items-center gap-2" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ Str::slug($modelo) }}"
                        aria-expanded="false"
                        aria-controls="collapse-{{ Str::slug($modelo) }}">

                        {{-- Logo del modelo --}}
                        <img src="{{ asset('hp-2.svg') }}"
                            alt="{{ $modelo }}"
                            style="width: 24px; height: 24px;">

                        {{-- Nombre + cantidad --}}
                        <span>{{ $modelo }} ({{ $equiposModelo->count() }} equipos)</span>
                    </button>
                </h2>
                <div id="collapse-{{ Str::slug($modelo) }}"
                    class="accordion-collapse collapse"
                    aria-labelledby="heading-{{ Str::slug($modelo) }}">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NÃºmero de serie</th>
                                        <th>Puesto actual</th>
                                        <th>Proveedor</th>
                                        <th>Fecha de ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equiposModelo as $equipo)
                                    <tr>
                                        <td>{{ $equipo->numero_serie }}</td>
                                        <td>{{ $equipo->puestoActual->nombre ?? 'N/A' }}</td>
                                        <td>{{ $equipo->proveedor->nombre ?? 'N/A' }}</td>
                                        <td>{{ $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible' }}</td>
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