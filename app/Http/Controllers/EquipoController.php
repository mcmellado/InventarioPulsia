<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon; // Importar Carbon

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
    $equipos = Equipo::with(['puestoActual', 'proveedor'])->where('modelo', $modelo)->get();
    $puestos = Puesto::all();

    return view('equipos.porModelo', compact('modelo', 'equipos', 'puestos'));
}


    public function crear()
    {
         $modelos = Equipo::select('modelo')->distinct()->pluck('modelo');

    // Retornar la vista pasando los modelos
    return view('equipos.crear', compact('modelos'));
    }

    public function guardarMultiple(Request $request)
{
    $request->validate([
        'numeros_serie' => 'required|string',
        'modelo_select' => 'required|string',
        'modelo' => 'required_if:modelo_select,otro|string|nullable',
        'fecha_ingreso' => 'nullable|date',  // Opcional
    ]);


    $modelo = $request->modelo_select === 'otro' ? $request->modelo : $request->modelo_select;

    $puestoAdmision = Puesto::where('nombre', 'Admisión')->first();

    if (!$puestoAdmision) {
        return redirect()->back()->withErrors(['puesto_actual_id' => 'No se encontró el puesto Admisión.']);
    }

    // Si no hay fecha, poner hoy
    $fechaIngreso = $request->fecha_ingreso ?: Carbon::today()->toDateString();

    $numerosSerie = preg_split('/\r\n|\r|\n/', trim($request->numeros_serie));

    $equiposGuardados = [];
    $duplicados = [];

    foreach ($numerosSerie as $numero) {
        $numero = trim($numero);
        if ($numero === '') {
            continue;
        }
        // Evitar duplicados
        if (Equipo::where('numero_serie', $numero)->exists()) {
            $duplicados[] = $numero;
            continue;
        }

        Equipo::create([
            'numero_serie' => $numero,
            'modelo' => $modelo,
            'fecha_ingreso' => $fechaIngreso,
            'puesto_actual_id' => $puestoAdmision->id,
        ]);

        $equiposGuardados[] = $numero;
    }

    if (count($duplicados) > 0) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['duplicados' => 'Los siguientes números de serie ya existen:'])
            ->with('duplicados', $duplicados);
    }

    return redirect()->back()->with('success', count($equiposGuardados) . ' equipos guardados correctamente.');
}

    public function eliminarPorModelo($modelo)
{
    $eliminados = Equipo::where('modelo', $modelo)->delete();

    return redirect()->back()->with('success', "$eliminados equipos del modelo '$modelo' eliminados correctamente.");
}

public function eliminarMultiple(Request $request)
{
    $ids = $request->input('equipos', []);
    Equipo::whereIn('id', $ids)->delete();

    return response()->json(['message' => 'Eliminado correctamente']);
}

public function trazabilidad(Equipo $equipo)
{
    $movimientos = $equipo->movimientos()
        ->with(['puestoOrigen', 'puestoDestino', 'usuario']) 
        ->orderByDesc('created_at') 
        ->get();

    $resultado = $movimientos->map(function($mov) {
        return [
            'fecha' => $mov->created_at->format('d-m-Y'),
            'puesto_origen' => $mov->puestoOrigen->nombre ?? '-',
            'puesto_destino' => $mov->puestoDestino->nombre ?? '-',
            'usuario' => $mov->usuario ? $mov->usuario->name : 'N/A',
            'observaciones' => $mov->observaciones ?? '',
        ];
    });

    return response()->json($resultado);
}

public function porPuesto($puestoId)
{
    $puesto = Puesto::findOrFail($puestoId);
    $equipos = Equipo::with('ultimoMovimiento') // o lo que necesites
                     ->where('puesto_actual_id', $puesto->id)
                     ->get();

    $puestos = Puesto::all();

    return view('equipos.porPuesto', compact('puesto', 'equipos', 'puestos'));
}


}
