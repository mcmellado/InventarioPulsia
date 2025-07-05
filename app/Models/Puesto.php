<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;


    protected $fillable = ['nombre'];

    public function equipos()

    {
        return $this->hasMany(Equipo::class, 'puesto_actual_id');
    }

    public function movimientosDesde() 
    {
        return $this->hasMany(Movimiento::class, 'puesto_origen_id');
    }

    
    public function movimientoHasta() 
    {
        return $this->hasMany(Movimiento::class, 'puesto_destino_id');
    }
    
}
