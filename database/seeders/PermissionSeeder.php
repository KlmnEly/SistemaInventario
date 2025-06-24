<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            // Categorias
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',

            // Marcas
            'ver-marca',
            'crear-marca',
            'editar-marca',
            'eliminar-marca',

            // Condiciones
            'ver-condicion',
            'crear-condicion',
            'editar-condicion',
            'eliminar-condicion',

            // Ubicaciones
            'ver-ubicacion',
            'crear-ubicacion',
            'editar-ubicacion',
            'eliminar-ubicacion',

            // Tipos de evento
            'ver-tipoEvento',
            'crear-tipoEvento',
            'editar-tipoEvento',
            'eliminar-tipoEvento',

            // Productos
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'mostrar-producto',
            'eliminar-producto',

            // Historiales
            'ver-historial',
            'crear-historial',
            'editar-historial',
            'mostrar-historial',

            // Roles
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role',

            // User
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',
        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }
    }
}
