<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\MovimientoController;


// Mostrar formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Procesar login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'user' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt(['user' => $credentials['user'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();
        return redirect()->intended('/equipos');
    }

    return back()->withErrors([
        'user' => 'Usuario o contraseña incorrectos.',
    ]);
});

// Ruta principal redirigiendo a /equipos y con nombre 'index'
Route::get('/', function () {
    return redirect()->route('equipos.index');
})->name('index');

// Rutas protegidas con middleware 'auth'
Route::middleware('auth')->group(function () {

    // Página principal de equipos con agrupación por modelo
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');

    // Mostrar equipos filtrados por modelo
    Route::get('/equipos/modelo/{modelo}', [EquipoController::class, 'porModelo'])->name('equipos.porModelo');

    // Listar todos los puestos con cantidad de equipos
    Route::get('/puestos', [PuestoController::class, 'index'])->name('puestos.index');

    // Ver los equipos de un puesto específico
    Route::get('/puestos/{nombre}', [PuestoController::class, 'porPuesto'])->name('puestos.porPuesto');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

});

// Mostrar formulario para mover un equipo
Route::get('/equipos/{id}/mover', [MovimientoController::class, 'crear'])->name('movimientos.crear');

// Guardar el movimiento
Route::post('/equipos/{id}/mover', [MovimientoController::class, 'guardar'])->name('movimientos.guardar');
