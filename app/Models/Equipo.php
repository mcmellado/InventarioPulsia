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
        'fecha_ingreso',
    ];

    /**
     * Relación con el puesto actual
     */
    public function puestoActual()
    {
        return $this->belongsTo(Puesto::class, 'puesto_actual_id');
    }

    /**
     * Relación con todos los movimientos
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    /**
     * Relación con el último movimiento registrado 
     */
    public function ultimoMovimiento()
    {
        return $this->hasOne(Movimiento::class)->latestOfMany();
    }

    public function puesto()
{
    return $this->belongsTo(Puesto::class);
}
}
