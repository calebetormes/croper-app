<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        if (! $adminRole) {
            $this->command->error('Role "Admin" não encontrada. Execute RolesSeeder antes.');

            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@croper.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('croper'),
                'role_id' => $adminRole->id,
                'observacoes' => 'Usuário padrão do sistema',
            ]
        );
    }
}
