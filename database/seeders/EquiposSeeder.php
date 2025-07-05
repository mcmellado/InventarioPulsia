<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // <-- Agrega esta línea

class EquiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $puestos = DB::table('puestos')->pluck('id', 'nombre');

        $equipos = [
            ['numero_serie' => 'ABC123', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['admisión'], 'fecha_ingreso' => now()],
            ['numero_serie' => '34S0YG3', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['admisión'], 'fecha_ingreso' => now()],
            ['numero_serie' => 'MIGUELCAGON', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['admisión'], 'fecha_ingreso' => now()],
            ['numero_serie' => 'DEF456', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['reparación'], 'fecha_ingreso' => now()->subDays(10)],
            ['numero_serie' => 'GHI789', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['calidad'], 'fecha_ingreso' => now()->subDays(20)],
            ['numero_serie' => 'JKL012', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['auditoría'], 'fecha_ingreso' => now()->subDays(30)],
            ['numero_serie' => 'MNO345', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['montaje'], 'fecha_ingreso' => now()->subDays(40)],
            ['numero_serie' => 'PQR678', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['pintura'], 'fecha_ingreso' => now()->subDays(50)],
            ['numero_serie' => 'STU901', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['logística'], 'fecha_ingreso' => now()->subDays(60)],
            ['numero_serie' => 'VWX234', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['venta'], 'fecha_ingreso' => now()->subDays(70)],
            ['numero_serie' => 'YZA567', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['teclados'], 'fecha_ingreso' => now()->subDays(80)],
            ['numero_serie' => 'BCD890', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['desmontaje'], 'fecha_ingreso' => now()->subDays(90)],
            ['numero_serie' => 'EFG123', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['auditoría'], 'fecha_ingreso' => now()->subDays(100)],
            ['numero_serie' => 'HIJ456', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['reparación'], 'fecha_ingreso' => now()->subDays(110)],
            ['numero_serie' => 'KLM789', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['calidad'], 'fecha_ingreso' => now()->subDays(120)],
            ['numero_serie' => 'NOP012', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['admisión'], 'fecha_ingreso' => now()->subDays(130)],
            ['numero_serie' => 'QRS345', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['montaje'], 'fecha_ingreso' => now()->subDays(140)],
            ['numero_serie' => 'TUV678', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['pintura'], 'fecha_ingreso' => now()->subDays(150)],
            ['numero_serie' => 'WXY901', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['logística'], 'fecha_ingreso' => now()->subDays(160)],
            ['numero_serie' => 'ZAB234', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['venta'], 'fecha_ingreso' => now()->subDays(170)],
        ];

        foreach ($equipos as $equipo) {
            DB::table('equipos')->insert($equipo);
        }
    }
}
