<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Equipos en Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
                                        <th>Número de serie</th>
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