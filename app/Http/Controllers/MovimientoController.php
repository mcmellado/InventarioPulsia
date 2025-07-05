<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Movimiento;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    public function crear($equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $puestos = Puesto::all();

        return view('movimientos.crear', compact('equipo', 'puestos'));
    }

    public function guardar(Request $request, $equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);

        $request->validate([
            'puesto_destino_id' => 'required|exists:puestos,id',
            'observaciones' => 'nullable|string|max:255',
        ]);

        Movimiento::create([
            'equipo_id' => $equipo->id,
            'usuario_id' => Auth::id(), 
            'puesto_origen_id' => $equipo->puesto_actual_id,
            'puesto_destino_id' => $request->puesto_destino_id,
            'observaciones' => $request->observaciones ?? '',
        ]);

        // Actualizar el equipo con el nuevo puesto
        $equipo->update([
            'puesto_actual_id' => $request->puesto_destino_id,
        ]);

        return redirect()->route('equipos.index')->with('success', 'Movimiento registrado correctamente.');
    }
}
