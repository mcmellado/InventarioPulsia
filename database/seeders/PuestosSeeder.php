<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Puesto;
use Illuminate\Support\Facades\DB;


class PuestosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $puestos = [
            "admisión",
            "auditoría",
            "desmontaje",
            "reparación",
            "pintura",
            "teclados",
            "montaje",
            "calidad",
            "logística",
            "venta"
        ];

        foreach ($puestos as $puesto) {
            Puesto::firstOrCreate(['nombre' => $puesto]);
        }
    }
}
