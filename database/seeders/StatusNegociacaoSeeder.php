<?php

namespace Database\Seeders;

use App\Models\StatusNegociacao;
use Illuminate\Database\Seeder;

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
            'nome' => 'Pausada',
            'descricao' => 'Negociação esta pausada pelo vendedor.',
            'cor' => '#EF4444', // vermelho
            'ordem' => 5,
            'icone' => 'ban',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Pagamento Recebido',
            'descricao' => 'Foi confirmado o recebimento do pagamento com sucesso.',
            'cor' => '#8B5CF6', // roxo
            'ordem' => 6,
            'icone' => 'check',
            'finaliza_negociacao' => true,
            'ativo' => true,
        ]);

        StatusNegociacao::create([
            'nome' => 'Entrega de Grãos Realizada',
            'descricao' => 'A entrega dos grãos foi concluída com sucesso.',
            'cor' => '#8B5CF6', // roxo
            'ordem' => 7,
            'icone' => 'check',
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
