<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias'; // Aseguramos que se refiera a la tabla 'categorias'

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relacion: Una categoría puede tener muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
