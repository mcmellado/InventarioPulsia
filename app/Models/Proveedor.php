<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    // Indica el nombre correcto de la tabla
    protected $table = 'proveedores';

    protected $fillable = ['nombre', 'contacto', 'direccion'];

    // Puedes agregar relaciones si quieres, pero no es obligatorio
}
