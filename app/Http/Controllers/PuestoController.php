<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Equipo;
use App\Models\Movimiento;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    public function index()
    {
        // Lista todos los puestos con la cantidad de equipos asignados
        $puestos = Puesto::withCount('equipos')->get();

        return view('puestos.index', compact('puestos'));
    }

    public function porPuesto($nombre)
    {
        $puesto = Puesto::where('nombre', $nombre)->firstOrFail();

        // Obtener equipos relacionados con el puesto
        $equipos = $puesto->equipos()->with('puestoActual')->get();

        // Para cada equipo agregar la última observación
        foreach ($equipos as $equipo) {
            $ultimoMovimiento = Movimiento::where('equipo_id', $equipo->id)
                ->orderByDesc('created_at')
                ->first();

            $equipo->ultima_observacion = $ultimoMovimiento ? $ultimoMovimiento->observaciones : '';
        }

        $puestos = Puesto::all();

        return view('puestos.porPuesto', compact('puesto', 'equipos', 'puestos'));
    }


}
