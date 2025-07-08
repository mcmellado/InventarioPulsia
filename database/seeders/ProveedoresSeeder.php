<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresSeeder extends Seeder
{
    public function run()
    {
        DB::table('proveedores')->insert([
            ['nombre' => 'ELECTRONIC_BAZAR'],
            ['nombre' => 'AMAYA'],
        ]);
    }
}
