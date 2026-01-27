<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Teclados',
                'slug' => 'teclados',
                'descripcion' => 'Teclados mecánicos personalizados de alta calidad',
            ],
            [
                'nombre' => 'Ratones',
                'slug' => 'ratones',
                'descripcion' => 'Ratones ergonómicos y gaming',
            ],
            [
                'nombre' => 'Accesorios',
                'slug' => 'accesorios',
                'descripcion' => 'Accesorios y repuestos para teclados',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
