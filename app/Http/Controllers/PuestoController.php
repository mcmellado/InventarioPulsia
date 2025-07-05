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
        // Busca el puesto por nombre
        $puesto = Puesto::where('nombre', $nombre)->firstOrFail();

        // Obtiene los equipos asignados a ese puesto
        $equipos = $puesto->equipos()->with('puestoActual')->get();

        return view('puestos.porPuesto', compact('puesto', 'equipos'));
    }
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'puesto_actual_id');
    }


}
