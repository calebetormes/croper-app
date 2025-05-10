<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusNegociacao;

class StatusNegociacaoSeeder extends Seeder
{
    public function run(): void
    {
        StatusNegociacao::create([
            'nome' => 'Rascunho',
            'descricao' => 'Negociação ainda em elaboração.',
            'cor' => '#9CA3AF', // cinza
            'ordem' => 1,
            'icone' => 'pencil',
            'finaliza_negociacao' => false,
            'ativo' => true,
        ]);

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
            'nome' => 'Cancelado',
            'descricao' => 'Negociação foi cancelada.',
            'cor' => '#EF4444', // vermelho
            'ordem' => 5,
            'icone' => 'ban',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Concluído',
            'descricao' => 'Negociação foi concluída com sucesso.',
            'cor' => '#8B5CF6', // roxo
            'ordem' => 6,
            'icone' => 'check',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);
    }
}
