<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\UserController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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

Route::get('/', function () {
    return redirect()->route('equipos.index');
})->name('index');

Route::middleware('auth')->group(function () {

    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');

    Route::get('/equipos/modelo/{modelo}', [EquipoController::class, 'porModelo'])->name('equipos.porModelo');

    Route::get('/equipos/crear-multiple', [EquipoController::class, 'crear'])->name('equipos.crearMultiple');

    Route::post('/equipos/guardar-multiple', [EquipoController::class, 'guardarMultiple'])->name('equipos.guardarMultiple');

    Route::get('/puestos', [PuestoController::class, 'index'])->name('puestos.index');

    Route::get('/puestos/{nombre}', [PuestoController::class, 'porPuesto'])->name('puestos.porPuesto');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

});

Route::get('/equipos/{id}/mover', [MovimientoController::class, 'crear'])->name('movimientos.crear');

Route::post('/equipos/{id}/mover', [MovimientoController::class, 'guardar'])->name('movimientos.guardar');

Route::post('/movimientos/multiple', [MovimientoController::class, 'crearMultiple'])->name('movimientos.multiple');

Route::post('/movimientos/guardar-multiple', [MovimientoController::class, 'guardarMultiple'])->name('movimientos.guardarMultiple');

Route::post('/observaciones/guardar-multiple', [MovimientoController::class, 'guardarObservacionesMultiple'])->name('observaciones.guardarMultiple');

Route::get('/equipos/crear', [EquipoController::class, 'crear'])->name('equipos.crear'); 
Route::post('/equipos/guardar', [EquipoController::class, 'guardar'])->name('equipos.guardar');
Route::delete('/equipos/lote/{modelo}', [EquipoController::class, 'eliminarPorModelo'])->name('equipos.eliminarPorModelo');
Route::post('/equipos/eliminar-multiple', [EquipoController::class, 'eliminarMultiple'])->name('equipos.eliminarMultiple');
Route::get('/equipos/{equipo}/trazabilidad', [EquipoController::class, 'trazabilidad']);
Route::get('/equipos/{id}/trazabilidad', [EquipoController::class, 'trazabilidad'])->name('equipos.trazabilidad');
Route::post('/movimientos/guardar-observacion', [MovimientoController::class, 'guardarObservacion'])->name('movimientos.guardarObservacion');
Route::get('/equipos/puesto/{puesto}', [EquipoController::class, 'porPuesto'])->name('equipos.porPuesto');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::patch('/equipos/{equipo}/toggle-stock', [EquipoController::class, 'toggleStock'])->name('equipos.toggleStock');

