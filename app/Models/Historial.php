<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;

    protected $table = 'historial'; // Aseguramos que se refiera a la tabla 'historial'

    protected $fillable = [
        'producto_id',
        'tipo_evento_id',
        'user_id',
        'ubicacion_antigua_id',
        'ubicacion_nueva_id',
        'condicion_antigua_id',
        'condicion_nueva_id',
        'fecha_evento',
        'descripcion'
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];

    // Relaciones:
    // Un registro de historial pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Un registro de historial pertenece a un tipo de evento
    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class);
    }

    // Un registro de historial es creado por un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un registro de historial tiene una ubicación antigua
    public function ubicacionAntigua()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_antigua_id');
    }

    // Un registro de historial tiene una ubicación nueva
    public function ubicacionNueva()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_nueva_id');
    }

    // Un registro de historial tiene una condicion antiguo
    public function estadoAntiguo()
    {
        return $this->belongsTo(Condicion::class, 'condicion_antigua_id');
    }

    // Un registro de historial tiene una condicion nuevo
    public function estadoNuevo()
    {
        return $this->belongsTo(Condicion::class, 'condicion_nueva_id');
    }
}
