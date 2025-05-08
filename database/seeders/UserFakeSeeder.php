<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserFakeSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 1 administrador
        User::factory()->create([
            'name' => 'Admin Master',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('admin123'),
            'role_id' => 4,
        ]);

        // Cria 1 gerente nacional
        User::factory()->create([
            'name' => 'Gerente Nacional',
            'email' => 'nacional@empresa.com',
            'password' => bcrypt('nacional123'),
            'role_id' => 3,
        ]);

        // Cria 10 gerentes comerciais
        User::factory(10)->create([
            'role_id' => 2,
        ]);

        // Cria 30 vendedores
        User::factory(30)->create([
            'role_id' => 1,
        ]);
    }
}
