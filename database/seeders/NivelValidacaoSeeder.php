<?php

namespace Database\Seeders;

use App\Models\NivelValidacao;
use Illuminate\Database\Seeder;

class NivelValidacaoSeeder extends Seeder
{
    public function run(): void
    {
        NivelValidacao::updateOrCreate(
            ['id' => 1],
            ['nome' => 'Vendedor', 'ordem_validacao' => 1]
        );

        NivelValidacao::updateOrCreate(
            ['id' => 2],
            ['nome' => 'Gerente Comercial', 'ordem_validacao' => 2]
        );

        NivelValidacao::updateOrCreate(
            ['id' => 3],
            ['nome' => 'Gerente Nacional', 'ordem_validacao' => 3]
        );

        NivelValidacao::updateOrCreate(
            ['id' => 4],
            ['nome' => 'Administrador', 'ordem_validacao' => 4]
        );
    }
}
