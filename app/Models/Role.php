<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles'; // Aseguramos que se refiere a la tabla 'roles'

    protected $fillable = [
        'nombre',
    ];

    // Relación: Un rol puede ser asignado a muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
