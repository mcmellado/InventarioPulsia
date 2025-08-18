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
                ['numero_serie' => '34S0YG3', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()],
                ['numero_serie' => 'MIGUELCAGON', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()],
                ['numero_serie' => 'DEF456', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['reparación'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(10)],
                ['numero_serie' => 'GHI789', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['calidad'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(20)],
                ['numero_serie' => 'JKL012', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['auditoría'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(30)],
                ['numero_serie' => 'MNO345', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['montaje'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(40)],
                ['numero_serie' => 'PQR678', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['pintura'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(50)],
                ['numero_serie' => 'STU901', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['logística'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(60)],
                ['numero_serie' => 'VWX234', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['venta'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(70)],
                ['numero_serie' => 'YZA567', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['teclados'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(80)],
                ['numero_serie' => 'BCD890', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['desmontaje'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(90)],
                ['numero_serie' => 'EFG123', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['auditoría'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(100)],
                ['numero_serie' => 'HIJ456', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['reparación'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(110)],
                ['numero_serie' => 'KLM789', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['calidad'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(120)],
                ['numero_serie' => 'NOP012', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['admisión'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(130)],
                ['numero_serie' => 'QRS345', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['montaje'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(140)],
                ['numero_serie' => 'TUV678', 'modelo' => '850 G5', 'puesto_actual_id' => $puestos['pintura'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(150)],
                ['numero_serie' => 'WXY901', 'modelo' => '840 G5', 'puesto_actual_id' => $puestos['logística'], 'proveedor_id' => $proveedores['ELECTRONIC_BAZAR'], 'fecha_ingreso' => now()->subDays(160)],
                ['numero_serie' => 'ZAB234', 'modelo' => '640 G5', 'puesto_actual_id' => $puestos['venta'], 'proveedor_id' => $proveedores['AMAYA'], 'fecha_ingreso' => now()->subDays(170)],
            ];

            foreach ($equipos as $equipo) {
                DB::table('equipos')->insert($equipo);
            }
        }
    }
