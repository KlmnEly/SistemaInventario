<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
    use HasFactory;

    protected $table = 'atributos'; // Aseguramos que se refiera a la tabla 'atributos'

    protected $fillable = [
        'producto_id',
        'nombre_atributo',
        'valor_atributo',
    ];

    // Relación: Un atributo pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
