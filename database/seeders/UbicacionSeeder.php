<?php

namespace Database\Seeders;

use App\Models\Ubicacione;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ubicaciones = [
            'Sala de Profesores',
            'Auditorio',
            'Biblioteca',
            'Administraciones',
            'Bienestar',
            'Enfermeria',
            'Mercadeo'
        ];

        foreach ($ubicaciones as $nombreUbicacion) {
            Ubicacione::firstOrCreate(
                ['nombre' => $nombreUbicacion],
                ['descripcion' => 'Descripción para ' . $nombreUbicacion]
            );
        }
    }
}
