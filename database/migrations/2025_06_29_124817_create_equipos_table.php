<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie')->unique();
            $table->string('modelo');

            $table->foreignId('puesto_actual_id')
                  ->nullable()
                  ->constrained('puestos')
                  ->onDelete('set null');

            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->constrained('proveedores')
                  ->onDelete('set null');

            $table->foreignId('comprador_id')
                  ->nullable()
                  ->constrained('compradores')
                  ->onDelete('set null');

            $table->date('fecha_ingreso')->nullable();
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipos');
    }
};
