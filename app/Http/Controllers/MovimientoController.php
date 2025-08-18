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

        
        $ultimoMovimiento = Movimiento::where('equipo_id', $equipo->id)
            ->orderByDesc('created_at')
            ->first();

        $equipo->ultima_observacion = $ultimoMovimiento ? $ultimoMovimiento->observaciones : '';

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

        $equipo->update([
            'puesto_actual_id' => $request->puesto_destino_id,
        ]);

        return redirect()->route('equipos.index')->with('success', 'Movimiento registrado correctamente.');
    }

   
    public function crearMultiple(Request $request)
    {
        $equipoIds = $request->input('equipos', []);
        $equipos = Equipo::whereIn('id', $equipoIds)->get();
        $puestos = Puesto::all();

        if ($equipos->isEmpty()) {
            return redirect()->route('equipos.index')->with('error', 'No seleccionaste ningún equipo.');
        }


        foreach ($equipos as $equipo) {
            $ultimoMovimiento = Movimiento::where('equipo_id', $equipo->id)
                ->orderByDesc('created_at')
                ->first();
            $equipo->ultima_observacion = $ultimoMovimiento ? $ultimoMovimiento->observaciones : '';
        }

        return view('movimientos.multiple', compact('equipos', 'puestos'));
    }


    public function guardarMultiple(Request $request)
    {
        $request->validate([
            'equipos' => 'required|array',
            'equipos.*' => 'exists:equipos,id',
            'puesto_destino_id' => 'required|exists:puestos,id',
            'observaciones' => 'nullable|array',
            'observaciones.*' => 'nullable|string|max:255',
        ]);

        $puestoDestino = Puesto::findOrFail($request->puesto_destino_id);

        foreach ($request->equipos as $equipoId) {
            $equipo = Equipo::findOrFail($equipoId);

            Movimiento::create([
                'equipo_id' => $equipo->id,
                'usuario_id' => Auth::id(),
                'puesto_origen_id' => $equipo->puesto_actual_id,
                'puesto_destino_id' => $request->puesto_destino_id,
                'observaciones' => $request->observaciones[$equipoId] ?? '',
            ]);

            $equipo->update([
                'puesto_actual_id' => $request->puesto_destino_id,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Movimientos múltiples registrados correctamente.',
                'puesto_nombre' => $puestoDestino->nombre,
            ]);
        }

        return redirect()->route('equipos.index')->with('success', 'Movimientos múltiples registrados correctamente.');
    }

    public function equiposPorPuesto($puestoId)
    {
        $puesto = Puesto::findOrFail($puestoId);
        $equipos = Equipo::where('puesto_actual_id', $puesto->id)->get();

        foreach ($equipos as $equipo) {
            $ultimoMovimiento = Movimiento::where('equipo_id', $equipo->id)
                ->orderByDesc('created_at')
                ->first();

            $equipo->ultima_observacion = $ultimoMovimiento ? $ultimoMovimiento->observaciones : '';
        }

        $puestos = Puesto::all();

        return view('equipos.porPuesto', compact('puesto', 'equipos', 'puestos'));
    }

    // Nuevo método para guardar observaciones sin mover equipos
    public function guardarObservacionesMultiple(Request $request)
    {
        $request->validate([
            'equipos' => 'required|array',
            'equipos.*' => 'exists:equipos,id',
            'observaciones' => 'nullable|array',
            'observaciones.*' => 'nullable|string|max:255',
        ]);

        foreach ($request->equipos as $equipoId) {
            $ultimoMovimiento = Movimiento::where('equipo_id', $equipoId)
                ->orderByDesc('created_at')
                ->first();

            if ($ultimoMovimiento) {
                $ultimoMovimiento->observaciones = $request->observaciones[$equipoId] ?? $ultimoMovimiento->observaciones;
                $ultimoMovimiento->save();
            }
        }

        return response()->json(['message' => 'Observaciones actualizadas correctamente']);
    }
}
