<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@croper.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('croper'),
                'role_id' => 4,
                'observacoes' => 'Usuário Admin do sistema',
            ]
        );

        // Gerente Nacional
        User::firstOrCreate(
            ['email' => 'nacional@croper.com'],
            [
                'name' => 'Gerente Nacional',
                'password' => Hash::make('croper'),
                'role_id' => 3,
                'observacoes' => 'Usuário Gerente Nacional do sistema',
            ]
        );

        // Gerente Comercial
        User::firstOrCreate(
            ['email' => 'comercial@croper.com'],
            [
                'name' => 'Gerente Comercial',
                'password' => Hash::make('croper'),
                'role_id' => 2,
                'observacoes' => 'Usuário Gerente Comercial do sistema',
            ]
        );

        // Vendedor
        User::firstOrCreate(
            ['email' => 'vendedor@croper.com'],
            [
                'name' => 'Vendedor',
                'password' => Hash::make('croper'),
                'role_id' => 1,
                'observacoes' => 'Usuário Vendedor do sistema',
            ]
        );
    }
}
