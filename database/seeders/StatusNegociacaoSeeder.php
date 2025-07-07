<?php

namespace Database\Seeders;

use App\Models\StatusNegociacao;
use Illuminate\Database\Seeder;

class StatusNegociacaoSeeder extends Seeder
{
    public function run(): void
    {

        StatusNegociacao::create([
            'nome' => 'Em análise',
            'descricao' => 'Negociação está em processo de avaliação.',
            'cor' => '#3B82F6', // azul
            'ordem' => 2,
            'icone' => 'search',
            'finaliza_negociacao' => false,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Aprovado',
            'descricao' => 'Negociação aprovada com sucesso.',
            'cor' => '#10B981', // verde
            'ordem' => 3,
            'icone' => 'check-circle',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Não Aprovado',
            'descricao' => 'Negociação não foi aprovada.',
            'cor' => '#F59E0B', // laranja
            'ordem' => 4,
            'icone' => 'x-circle',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Concluído',
            'descricao' => 'Todas as etapas da negociação foram concluída com sucesso.',
            'cor' => '#8B5CF6', // roxo
            'ordem' => 8,
            'icone' => 'check',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);
    }
}
