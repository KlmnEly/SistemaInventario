<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacione extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones'; // Aseguramos que se refiere a la tabla 'ubicaciones'

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Relaciones:
    // Una ubicación puede tener muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Una ubicación puede ser la ubicación antigua en muchos historiales
    public function historialAntiguo()
    {
        return $this->hasMany(Historial::class, 'ubicacion_antigua');
    }

    // Una ubicación puede ser la ubicación nueva en muchos historiales
    public function historialNuevo()
    {
        return $this->hasMany(Historial::class, 'ubicacion_nueva');
    }
}
