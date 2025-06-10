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

            // preços valorizados
            'preco_produto_valorizado_rs' => 450.00,
            'preco_produto_valorizado_us' => 90.00,

            // data do snapshot
            'data_atualizacao_snap_precos_produtos' => now()->toDateString(),
        ]);
    }
}