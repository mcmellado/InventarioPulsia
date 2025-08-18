<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir varios equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
