<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas'; // Aseguramos que se refiera a la tabla 'marcas'

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relación: Una marca puede aplicarse a muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
