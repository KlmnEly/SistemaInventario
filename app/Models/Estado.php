<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados'; // Aseguramos que se refiera a la tabla 'estados'

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relaciones:
    // Un estado puede aplicarse a muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Un estado puede ser el estado antiguo en muchos historiales
    public function historialAntiguo()
    {
        return $this->hasMany(Historial::class, 'estado_antiguo');
    }

    // Un estado puede ser el estado nuevo en muchos historiales
    public function historialNuevo()
    {
        return $this->hasMany(Historial::class, 'estado_nuevo');
    }
}
