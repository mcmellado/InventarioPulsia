<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Exports\AlbaranExport;
use Maatwebsite\Excel\Facades\Excel;

class AlbaranController extends Controller
{
  
    public function show()
    {
        return view('albaran');
    }


    public function export(Request $request)
    {
       
        $numerosSerie = preg_split("/\r\n|\n|\r/", $request->numeros_serie);
        $numerosSerie = array_map('trim', $numerosSerie);
        $numerosSerie = array_filter($numerosSerie); 

        return Excel::download(new AlbaranExport($numerosSerie), 'albaran.xlsx');
    }
}
