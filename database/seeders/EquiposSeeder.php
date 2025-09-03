<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquiposSeeder extends Seeder
{
    public function run()
    {
        $puestos = DB::table('puestos')->pluck('id', 'nombre');
        $proveedores = DB::table('proveedores')->pluck('id', 'nombre');

        $equipos = [
            // Modelos ya existentes
            ['numero_serie' => '34S0YG3', 'modelo' => '640 G5', 'marca' => 'hp-2.svg', 'configuracion' => 'i5-8th 256GB 8GB', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()],
            ['numero_serie' => 'MIGUELCAGON', 'modelo' => '850 G5', 'marca' => 'hp-2.svg', 'configuracion' => 'i7-8th 512GB 16GB', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()],
            ['numero_serie' => 'DEF456', 'modelo' => '840 G5', 'marca' => 'hp-2.svg', 'configuracion' => 'i5-7th 256GB 8GB', 'puesto_actual_id' => $puestos['reparación'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(10)],

            // Nuevos modelos HP
            ['numero_serie' => 'HP1234', 'modelo' => 'EliteBook 830 G6', 'marca' => 'hp-2.svg', 'configuracion' => 'i5-8th 256GB 8GB', 'puesto_actual_id' => $puestos['montaje'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(5)],
            ['numero_serie' => 'HP5678', 'modelo' => 'ProBook 450 G7', 'marca' => 'hp-2.svg', 'configuracion' => 'i7-10th 512GB 16GB', 'puesto_actual_id' => $puestos['calidad'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(15)],
            ['numero_serie' => 'HP9101', 'modelo' => 'EliteBook 745 G5', 'marca' => 'hp-2.svg', 'configuracion' => 'i5-7th 256GB 8GB', 'puesto_actual_id' => $puestos['logística'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(25)],

            // Modelos Dell
            ['numero_serie' => 'DELL001', 'modelo' => 'Latitude 5400', 'marca' => 'dell-2.png', 'configuracion' => 'i5-8th 256GB 8GB', 'puesto_actual_id' => $puestos['venta'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(35)],
            ['numero_serie' => 'DELL002', 'modelo' => 'Latitude 7400', 'marca' => 'dell-2.png', 'configuracion' => 'i7-8th 512GB 16GB', 'puesto_actual_id' => $puestos['pintura'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(45)],
            ['numero_serie' => 'DELL003', 'modelo' => 'OptiPlex 7070', 'marca' => 'dell-2.png', 'configuracion' => 'i5-9th 256GB 8GB', 'puesto_actual_id' => $puestos['reparación'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(55)],

            // Modelos Lenovo
            ['numero_serie' => 'LEN001', 'modelo' => 'ThinkPad T490', 'marca' => 'lenovo-2.svg', 'configuracion' => 'i5-8th 256GB 8GB', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(65)],
            ['numero_serie' => 'LEN002', 'modelo' => 'ThinkPad X1 Carbon', 'marca' => 'lenovo-2.svg', 'configuracion' => 'i7-10th 512GB 16GB', 'puesto_actual_id' => $puestos['auditoría'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(75)],
            ['numero_serie' => 'LEN003', 'modelo' => 'IdeaPad 5 15IIL05', 'marca' => 'lenovo-2.svg', 'configuracion' => 'i5-10th 256GB 8GB', 'puesto_actual_id' => $puestos['montaje'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(85)],

           ];

        foreach ($equipos as $equipo) {
            DB::table('equipos')->insert($equipo);
        }
    }
}
