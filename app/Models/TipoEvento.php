<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvento extends Model
{
    use HasFactory;

    protected $table = 'tipo_evento'; // Aseguramos que se refiere a la tabla 'tipo_evento'

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación: Un tipo de evento puede aparecer en muchos historiales
    public function historial()
    {
        return $this->hasMany(Historial::class);
    }
}
