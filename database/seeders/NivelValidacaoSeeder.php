<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelValidacaoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveis_validacao')->insert(['nome' => 'Gerente Comercial', 'ordem_validacao' => '1']);
        DB::table('niveis_validacao')->insert(['nome' => 'Gerente Nacional', 'ordem_validacao' => '2']);
        DB::table('niveis_validacao')->insert(['nome' => 'Administrador', 'ordem_validacao' => '3']);
    }
}
