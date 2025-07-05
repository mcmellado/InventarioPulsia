<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
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
    $equipos = $puesto->equipos()->with('puestoActual')->get();
    $puestos = Puesto::all(); // <-- Aquí agregas esta línea

    return view('puestos.porPuesto', compact('puesto', 'equipos', 'puestos'));
    }
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'puesto_actual_id');
    }
}
