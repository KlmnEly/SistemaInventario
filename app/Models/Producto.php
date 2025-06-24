<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; // Asegura que se mapea a la tabla 'productos'

    protected $fillable = [
        'marca_id',
        'categoria_id',
        'ubicacion_id',
        'condicion_id',
        'codigo',
        'nombre',
        'serial',
    ];

    // Relaciones:
    // Un producto pertenece a una marca
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    // Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Un producto pertenece a una ubicación
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacione::class);
    }

    // Un producto pertenece a una condicion
    public function condicion()
    {
        return $this->belongsTo(Condicione::class);
    }

    // Un producto puede tener muchos atributos
    public function atributos()
    {
        return $this->hasMany(Atributo::class);
    }

    // Un producto puede tener muchos registros en el historial
    public function historial()
    {
        return $this->hasMany(Historial::class);
    }
}
