<?php

namespace Database\Seeders;

use App\Models\TipoEvento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoEvento::factory()->count(10)->create(); // Crea 10 tipos de eventos de ejemplo
    }
}
