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

        $puestos = Puesto::withCount('equipos')->get();

        return view('puestos.index', compact('puestos'));
    }

    public function porPuesto($nombre)
    {
        $puesto = Puesto::where('nombre', $nombre)->firstOrFail();


        $equipos = $puesto->equipos()->with('puestoActual')->get();


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
