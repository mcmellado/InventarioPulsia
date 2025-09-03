<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir varios equipos</title>
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
<body>

<div class="container mt-5">
    <h1 class="mb-4">Añadir varios equipos</h1>

    @if (session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function () {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 15000);
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @if(session('duplicados'))
                <p><strong>Números de serie duplicados:</strong></p>
                <ul>
                    @foreach(session('duplicados') as $dup)
                        <li>{{ $dup }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <form action="{{ route('equipos.guardarMultiple') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="numeros_serie" class="form-label">Números de serie (uno por línea)</label>
            <textarea class="form-control @error('numeros_serie') is-invalid @enderror" id="numeros_serie" name="numeros_serie" rows="8" placeholder="Escribe o pega aquí los números de serie, uno por línea" required>{{ old('numeros_serie') }}</textarea>
            @error('numeros_serie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

   
        <div class="mb-3">
            <label for="modelo_select" class="form-label">Modelo</label>
            <select class="form-select @error('modelo') is-invalid @enderror" id="modelo_select" name="modelo_select" required>
                <option value="">-- Selecciona un modelo --</option>
                @foreach($modelos as $modelo)
                    <option value="{{ $modelo }}" {{ old('modelo_select') == $modelo ? 'selected' : '' }}>{{ $modelo }}</option>
                @endforeach
                <option value="otro" {{ old('modelo_select') == 'otro' ? 'selected' : '' }}>Otro (especificar)</option>
            </select>
            @error('modelo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="nuevo_modelo_div" style="display: {{ old('modelo_select') == 'otro' ? 'block' : 'none' }};">
            <label for="modelo" class="form-label">Nuevo modelo</label>
            <input type="text" class="form-control @error('modelo') is-invalid @enderror" id="modelo" name="modelo" value="{{ old('modelo') }}" {{ old('modelo_select') == 'otro' ? 'required' : '' }}>
            @error('modelo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    
        <div class="mb-3">
            <label for="proveedor_select" class="form-label">Proveedor</label>
            <select class="form-select @error('proveedor') is-invalid @enderror" id="proveedor_select" name="proveedor_select" required>
                <option value="">-- Selecciona un proveedor --</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ old('proveedor_select') == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->nombre }}
                    </option>
                @endforeach
                <option value="otro" {{ old('proveedor_select') == 'otro' ? 'selected' : '' }}>Otro (especificar)</option>
            </select>
            @error('proveedor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" id="nuevo_proveedor_div" style="display: {{ old('proveedor_select') == 'otro' ? 'block' : 'none' }};">
            <label for="nuevo_proveedor" class="form-label">Nuevo proveedor</label>
            <input type="text" class="form-control @error('nuevo_proveedor') is-invalid @enderror" id="nuevo_proveedor" name="nuevo_proveedor" value="{{ old('nuevo_proveedor') }}" {{ old('proveedor_select') == 'otro' ? 'required' : '' }}>
            @error('nuevo_proveedor')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

     
        <div class="mb-3">
            <label for="fecha_ingreso" class="form-label">Fecha de ingreso <small class="text-muted">(Opcional, si no se pone será hoy)</small></label>
            <input type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}">
            @error('fecha_ingreso')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar equipos</button>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modeloSelect = document.getElementById("modelo_select");
        const nuevoModeloDiv = document.getElementById("nuevo_modelo_div");
        const nuevoModeloInput = document.getElementById("modelo");

        function toggleNuevoModelo() {
            if (modeloSelect.value === "otro") {
                nuevoModeloDiv.style.display = "block";
                nuevoModeloInput.required = true;
            } else {
                nuevoModeloDiv.style.display = "none";
                nuevoModeloInput.required = false;
            }
        }

        modeloSelect.addEventListener("change", toggleNuevoModelo);
        toggleNuevoModelo();


        const proveedorSelect = document.getElementById("proveedor_select");
        const nuevoProveedorDiv = document.getElementById("nuevo_proveedor_div");
        const nuevoProveedorInput = document.getElementById("nuevo_proveedor");

        function toggleNuevoProveedor() {
            if (proveedorSelect.value === "otro") {
                nuevoProveedorDiv.style.display = "block";
                nuevoProveedorInput.required = true;
            } else {
                nuevoProveedorDiv.style.display = "none";
                nuevoProveedorInput.required = false;
            }
        }

        proveedorSelect.addEventListener("change", toggleNuevoProveedor);
        toggleNuevoProveedor();
    });
</script>

</body>
</html>
