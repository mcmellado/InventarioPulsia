<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Puesto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class EquipoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $equipos = Equipo::with('puestoActual')->get();
        $equiposPorModelo = $equipos->groupBy('modelo');
        return view('equipos.index', compact('equiposPorModelo'));
    }
    
    public function porModelo($modelo)
    {
        $equipos = Equipo::with('puestoActual')->where('modelo', $modelo)->get();
        $puestos = Puesto::all();

        return view('equipos.porModelo', compact('modelo', 'equipos', 'puestos'));
    }
}
