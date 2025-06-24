<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Computadores',
            'Impresoras',
            'Proyectores',
            'Televisores',
            'Telefonos',
            'Camaras'
        ];

        foreach ($categorias as $nombreCategoria) {
            Categoria::firstOrCreate(
                ['nombre' => $nombreCategoria],
                ['descripcion' => 'Descripción para ' . $nombreCategoria]
            );
        }
    }
}
