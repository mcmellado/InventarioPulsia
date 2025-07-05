<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;


    protected $fillable = [
        'numero_serie',
        'modelo',
        'puesto_actual_id',
        'fecha_ingreso'
    ];

    // RELACION CON EL PUESTO ACTUAL
    public function puestoActual()
    {
        return $this->belongsTo(Puesto::class, 'puesto_actual_id');
    }
    

    // RELACION CON TODOS SUS MOVIMIENTOS
    public function movimientos()
     {
        return $this->hasMany(Movimiento::class);
     }


     public function ultimoMovimiento()
{
    return $this->hasOne(\App\Models\Movimiento::class)->latestOfMany();
}



}
