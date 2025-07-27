<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipo_id',
        'componente',
        'estado',
        'observacion',
        'fecha_revision',
        'usuario_id',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
