<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Teclado Mecánico RGB',
                'slug' => 'teclado-mecanico-rgb',
                'descripcion' => 'Teclado mecánico con switches personalizables y retroiluminación RGB. Perfecto para gaming y escritura profesional.',
                'precio' => 129.99,
                'stock' => 15,
                'imagen' => 'teclado-rgb.jpg',
                'categoria_id' => 1,
                'destacado' => true,
            ],
            [
                'nombre' => 'Teclado 60% Compacto',
                'slug' => 'teclado-60-compacto',
                'descripcion' => 'Teclado mecánico compacto 60% con diseño minimalista.',
                'precio' => 99.99,
                'stock' => 20,
                'imagen' => 'teclado-60.jpg',
                'categoria_id' => 1,
                'destacado' => true,
            ],
            [
                'nombre' => 'Ratón Gaming Pro',
                'slug' => 'raton-gaming-pro',
                'descripcion' => 'Ratón gaming con sensor de alta precisión y 8 botones programables.',
                'precio' => 59.99,
                'stock' => 25,
                'imagen' => 'raton-gaming.jpg',
                'categoria_id' => 2,
                'destacado' => true,
            ],
            [
                'nombre' => 'Ratón Inalámbrico',
                'slug' => 'raton-inalambrico',
                'descripcion' => 'Ratón inalámbrico ergonómico con batería de larga duración.',
                'precio' => 34.99,
                'stock' => 30,
                'imagen' => 'raton-inalambrico.jpg',
                'categoria_id' => 2,
                'destacado' => false,
            ],
            [
                'nombre' => 'Keycaps ABS Premium',
                'slug' => 'keycaps-abs-premium',
                'descripcion' => 'Set de keycaps ABS de alta calidad con diferentes perfiles.',
                'precio' => 45.99,
                'stock' => 40,
                'imagen' => 'keycaps.jpg',
                'categoria_id' => 3,
                'destacado' => false,
            ],
            [
                'nombre' => 'Stabilizers Estabilizadores',
                'slug' => 'stabilizers',
                'descripcion' => 'Estabilizadores para teclados mecánicos de banda ancha.',
                'precio' => 24.99,
                'stock' => 50,
                'imagen' => 'stabilizers.jpg',
                'categoria_id' => 3,
                'destacado' => false,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
