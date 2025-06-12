<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;

    protected $table = 'presentaciones'; // Aseguramos que se refiere a la tabla 'presentaciones'

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relación: Una presentación puede aplicarse a muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
