<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            'Hawlett Packard',
            'Lenovo',
            'Asus',
            'Acer',
            'Dell',
            'Apple',
            'Epson',
            'Canon',
            'Brother',
            'Ricoh',
            'Samsung',
            'Motorola',
            'Huawei',
            'Xiaomi',
            'LG',
            'Samsung',
            'Sony',
            'BenQ',
            'ViewSonic',
            'Nikon',
            'GoPro'
        ];

        foreach ($marcas as $nombreMarca) {
            Marca::firstOrCreate(
                ['nombre' => $nombreMarca],
                ['descripcion' => 'Descripción para ' . $nombreMarca]
            );
        }
    }
}
