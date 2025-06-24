<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condicione extends Model
{
    use HasFactory;

    protected $table = 'condiciones'; // Aseguramos que se refiera a la tabla 'condiciones'

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relaciones:
    // Una condicion puede aplicarse a muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Una condicion puede ser la condicion antigua en muchos historiales
    public function historialAntiguo()
    {
        return $this->hasMany(Historial::class, 'condicion_antigua_id');
    }

    // Una condicion puede ser la condicion nueva en muchos historiales
    public function historialNuevo()
    {
        return $this->hasMany(Historial::class, 'condicion_nueva_id');
    }
}
