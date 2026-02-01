<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@ukey.com',
            'password' => Hash::make('admin123'),
            'telefono' => '600000000',
            'direccion' => 'Calle Admin, 1',
            'rol' => 'Admin',
        ]);

        // Cliente user
        Usuario::create([
            'nombre' => 'Cliente Test',
            'email' => 'cliente@ukey.com',
            'password' => Hash::make('cliente123'),
            'telefono' => '601111111',
            'direccion' => 'Calle Cliente, 2',
            'rol' => 'Cliente',
        ]);
    }
}
