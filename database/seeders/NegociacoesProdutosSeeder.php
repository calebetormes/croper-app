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
            'snap_produto_preco_rs' => 500.00,
            'snap_produto_preco_us' => 100.00,
            'data_atualizacao_snap_precos_produtos' => now()->toDateString(),

            'negociacao_produto_fator_valorizacao' => 1.2,
            'negociacao_produto_preco_virtual_rs' => 450.00,
            'negociacao_produto_preco_virtual_us' => 90.00,

        ]);
    }
}