<?php

namespace Database\Seeders;

use App\Models\TipoEvento;
use Illuminate\Database\Seeder;

class TipoEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definimos los tipos de evento que queremos asegurar que existan
        $tiposEventos = [
            'Reubicacion',
            'Cambio de Piezas',
            'Mantenimiento',
            'De baja'
        ];

        foreach ($tiposEventos as $nombreTipo) {
            TipoEvento::firstOrCreate(
                ['nombre' => $nombreTipo], 
                ['descripcion' => 'Descripción para ' . $nombreTipo]
            );
        }
    }
}
