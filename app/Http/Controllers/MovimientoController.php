<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Movimiento;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    // Mostrar formulario para mover un equipo
    public function crear($equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $puestos = Puesto::all();

        return view('movimientos.crear', compact('equipo', 'puestos'));
    }

    // Guardar movimiento para un solo equipo
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

    // Mostrar formulario para mover múltiples equipos seleccionados
    public function crearMultiple(Request $request)
    {
        $equipoIds = $request->input('equipos', []);
        $equipos = Equipo::whereIn('id', $equipoIds)->get();
        $puestos = Puesto::all();

        if ($equipos->isEmpty()) {
            return redirect()->route('equipos.index')->with('error', 'No seleccionaste ningún equipo.');
        }

        return view('movimientos.multiple', compact('equipos', 'puestos'));
    }

    public function guardarMultiple(Request $request)
    {
        $request->validate([
            'equipos' => 'required|array',
            'equipos.*' => 'exists:equipos,id',
            'puesto_destino_id' => 'required|exists:puestos,id',
            'observaciones' => 'nullable|string|max:255',
        ]);

        foreach ($request->equipos as $equipoId) {
            $equipo = Equipo::findOrFail($equipoId);

            Movimiento::create([
                'equipo_id' => $equipo->id,
                'usuario_id' => Auth::id(),
                'puesto_origen_id' => $equipo->puesto_actual_id,
                'puesto_destino_id' => $request->puesto_destino_id,
                'observaciones' => $request->observaciones ?? '',
            ]);

            $equipo->update([
                'puesto_actual_id' => $request->puesto_destino_id,
            ]);
        }

        return redirect()->route('equipos.index')->with('success', 'Movimientos múltiples registrados correctamente.');
    }
}
