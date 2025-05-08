<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@croper.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('croper'),
                'role_id' => 4,
                'observacoes' => 'Usuário Admin do sistema',
            ]
        );
    }
}
