<?php

namespace Database\Seeders;

use App\Models\NegociacaoProduto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NegociacoesProdutosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Recupera um ID existente de negociacao e produto
        $negociacaoId = DB::table('negociacoes')->value('id');
        $produtoId = DB::table('produtos')->value('id');

        if (!$negociacaoId || !$produtoId) {
            $this->command->info('Tabela negociacoes ou produtos está vazia. Seeder ignorado.');

            return;
        }

        NegociacaoProduto::create([
            'negociacao_id' => $negociacaoId,
            'produto_id' => $produtoId,
            'volume' => 100.00,

            // índice de valorização (antigo fator)
            'indice_valorizacao' => 1.20,

            // snapshot de preços
            'snap_produto_preco_rs' => 500.00,
            'snap_produto_preco_us' => 100.00,
            'snap_produto_custo_rs' => 400.00,
            'snap_produto_custo_us' => 80.00,
            // preços totais
            'preco_total_produto_negociacao_rs' => 600.00, // (snap_produto_preco_rs * volume)
            'preco_total_produto_negociacao_us' => 120.00, // (snap_produto_preco_us * volume)
            'custo_total_produto_negociacao_rs' => 400.00, // (snap_produto_custo_rs * volume)
            'custo_total_produto_negociacao_us' => 180.00, // (snap_produto_custo_us * volume)
            //
            // margem de faturamento
            'margem_faturamento_rs' => 10,
            'margem_faturamento_us' => 10,

            // preços valorizados
            'preco_produto_valorizado_rs' => 450.00,
            'preco_produto_valorizado_us' => 90.00,

            // data do snapshot
            'data_atualizacao_snap_precos_produtos' => now()->toDateString(),
        ]);
    }
}