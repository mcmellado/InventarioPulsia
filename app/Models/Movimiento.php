<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'equipo_id',
        'usuario_id',
        'puesto_origen_id',
        'puesto_destino_id',
        'observaciones',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function puestoOrigen()
    {
        return $this->belongsTo(Puesto::class, 'puesto_origen_id');
    }

    public function puestoDestino()
    {
        return $this->belongsTo(Puesto::class, 'puesto_destino_id');
    }
}
