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
        'ubicacion_antigua',
        'ubicacion_nueva',
        'estado_antiguo',
        'estado_nuevo',
        'fecha_evento',
        'descripcion_evento'
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

    // Un registro de historial tiene un estado antiguo
    public function estadoAntiguo()
    {
        return $this->belongsTo(Estado::class, 'estado_antiguo_id');
    }

    // Un registro de historial tiene un estado nuevo
    public function estadoNuevo()
    {
        return $this->belongsTo(Estado::class, 'estado_nuevo_id');
    }
}
