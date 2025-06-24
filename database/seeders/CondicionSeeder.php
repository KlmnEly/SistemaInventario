<?php

namespace Database\Seeders;

use App\Models\Condicione;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CondicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condiciones = [
            'Fallando',
            'En Uso',
            'Disponible',
            'Fuera de servicio',
            'En garantia',
            'Extraviado',
            'Robado'
        ];

        foreach ($condiciones as $nombreCondiciones) {
            Condicione::firstOrCreate(
                ['nombre' => $nombreCondiciones],
                ['descripcion' => 'Descripción para ' . $nombreCondiciones]
            );
        }
    }
}
