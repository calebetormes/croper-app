<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusNegociacaoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_negociacoes')->insert(['nome' => 'Rascunho', 'descricao' => 'Negociação em rascunho']);
        DB::table('status_negociacoes')->insert(['nome' => 'Em análise', 'descricao' => 'Negociação aguardando análise']);
        DB::table('status_negociacoes')->insert(['nome' => 'Cancelado', 'descricao' => 'Negociação cancelada']);
        DB::table('status_negociacoes')->insert(['nome' => 'Aprovado', 'descricao' => 'Negociação aprovada']);
        DB::table('status_negociacoes')->insert(['nome' => 'Não Aprovado', 'descricao' => 'Negociação não aprovada']);
        DB::table('status_negociacoes')->insert(['nome' => 'Concluido', 'descricao' => 'Negociação concluída']);
    }
}
