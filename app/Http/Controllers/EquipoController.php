<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon; 
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;



class EquipoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $user = Auth::user();

        $equipos = Equipo::with('puestoActual')->get();


        if ($user->puesto !== 'admin') {
            $equipos = $equipos->filter(function($equipo) use ($user) {
                return redirect()->route('equipos.porPuesto', $user->puesto);    
            });
        }

        $equipos = Equipo::with('puestoActual')->get();
        $equiposPorModelo =  $equipos->groupBy('modelo');
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
    $proveedores = \App\Models\Proveedor::all(); 
    return view('equipos.crear', compact('modelos', 'proveedores'));
}


    public function guardarMultiple(Request $request)
{
    $request->validate([
    'numeros_serie' => 'required|string',
    'modelo_select' => 'required|string',
    'modelo' => 'required_if:modelo_select,otro|string|nullable',
    'proveedor_select' => 'required|string',
    'nuevo_proveedor' => 'required_if:proveedor_select,otro|string|nullable',
    'fecha_ingreso' => 'nullable|date',
]);

    $modelo = $request->modelo_select === 'otro' ? $request->modelo : $request->modelo_select;

    $puestoAdmision = Puesto::where('nombre', 'Admisión')->first();

    if (!$puestoAdmision) {
        return redirect()->back()->withErrors(['puesto_actual_id' => 'No se encontró el puesto Admisión.']);
    }

    $fechaIngreso = $request->fecha_ingreso ?: Carbon::today()->toDateString();
    $numerosSerie = preg_split('/\r\n|\r|\n/', trim($request->numeros_serie));

    $equiposGuardados = [];
    $duplicados = [];

    foreach ($numerosSerie as $numero) {
        $numero = trim($numero);
        if ($numero === '') continue;

        if (Equipo::where('numero_serie', $numero)->exists()) {
            $duplicados[] = $numero;
            continue;
        }

            $modelo = $request->modelo_select === 'otro' ? $request->modelo : $request->modelo_select;


            if ($request->proveedor_select === 'otro') {
                $nuevoProveedor = new \App\Models\Proveedor();
                $nuevoProveedor->nombre = $request->nuevo_proveedor;
                $nuevoProveedor->save();
                $proveedor_id = $nuevoProveedor->id;
            } else {
                $proveedor_id = $request->proveedor_select;
            }


             Equipo::create([
                'numero_serie' => $numero,
                'modelo' => $modelo,
                'fecha_ingreso' => $fechaIngreso,
                'proveedor_id' => $proveedor_id,
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
    $equipos = Equipo::with('ultimoMovimiento') 
                     ->where('puesto_actual_id', $puesto->id)
                     ->get();

    $puestos = Puesto::all();

    return view('equipos.porPuesto', compact('puesto', 'equipos', 'puestos'));
}

        public function toggleStock(Equipo $equipo)
    {
        
        $equipo->stock = !$equipo->stock;
        $equipo->save();

        return response()->json([
            'success' => true,
            'stock' => $equipo->stock
        ]);
    }


    public function stock()
{
    $equipos = Equipo::where('stock', true)->get();

    return view('equipos.stock', compact('equipos'));
}

}
