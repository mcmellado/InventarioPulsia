<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Puesto;
use Illuminate\Http\Request;
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

    public function crear()
    {
        $puestos = Puesto::all(); 
        return view('equipos.crear', compact('puestos'));
    }

    // Nuevo método para guardar múltiples equipos
    public function guardarMultiple(Request $request)
    {
        $request->validate([
            'numeros_serie' => 'required|string',
            'modelo' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'puesto_actual_id' => 'required|exists:puestos,id',
        ]);

        // Separa los números de serie por líneas
        $numerosSerie = preg_split('/\r\n|\r|\n/', trim($request->numeros_serie));

        $equiposGuardados = [];

        foreach ($numerosSerie as $numero) {
            $numero = trim($numero);
            if ($numero === '') {
                continue;
            }
            // Aquí puedes agregar lógica para evitar duplicados si quieres
            $equipo = Equipo::create([
                'numero_serie' => $numero,
                'modelo' => $request->modelo,
                'fecha_ingreso' => $request->fecha_ingreso,
                'puesto_actual_id' => $request->puesto_actual_id,
            ]);
            $equiposGuardados[] = $equipo;
        }

        return redirect()->route('equipos.index')->with('success', count($equiposGuardados) . ' equipos guardados correctamente.');
    }
}
