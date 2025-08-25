<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlbaranExport implements FromCollection, WithHeadings
{
    protected $numerosSerie;

    public function __construct(array $numerosSerie)
    {
        $this->numerosSerie = $numerosSerie;
    }

    public function collection()
    {
        return Equipo::whereIn('numero_serie', $this->numerosSerie)
            ->with(['puestoActual', 'proveedor'])
            ->get()
            ->map(function ($equipo) {
                return [
                    'numero_serie' => $equipo->numero_serie,
                    'modelo' => $equipo->modelo,
                    'puesto_actual' => $equipo->puestoActual->nombre ?? 'N/A',
                    'proveedor' => $equipo->proveedor->nombre ?? 'N/A',
                    'fecha_ingreso' =>  $equipo->fecha_ingreso ? \Carbon\Carbon::parse($equipo->fecha_ingreso)->format('d-m-Y') : 'No disponible'
                ];
            });
    }

    public function headings(): array
    {
        return ['Número de Serie', 'Modelo', 'Puesto Actual', 'Proveedor', 'Fecha de Ingreso'];
    }
}
