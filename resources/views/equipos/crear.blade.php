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
        setTimeout(function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 15000); // 15000 ms = 15 segundos
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
            <label for="modelo" class="form-label">Modelo</label>
            <input type="text" class="form-control @error('modelo') is-invalid @enderror" id="modelo" name="modelo" required value="{{ old('modelo') }}">
            @error('modelo')
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

</body>
</html>
