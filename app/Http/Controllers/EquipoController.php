<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Equipo;

class EquipoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        // Trae todos los equipos con su relación
        $equipos = Equipo::with('puestoActual')->get();

        // Agrupa los equipos por modelo
        $equiposPorModelo = $equipos->groupBy('modelo');

        // Pasa los datos a la vista
        return view('equipos.index', compact('equiposPorModelo'));
    }

    // Método para mostrar equipos de un modelo específico
    public function porModelo($modelo)
    {
        $equipos = Equipo::with('puestoActual')->where('modelo', $modelo)->get();

        return view('equipos.porModelo', compact('modelo', 'equipos'));
    }
}
