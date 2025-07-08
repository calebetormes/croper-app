<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;

class DetalhesProdutoHidden
{
    public static function section(): Section
    {
        return Section::make('Detalhes do Produto')
            ->columns(3)
            ->schema([
                // Índice de Valorização
                Hidden::make('indice_valorizacao')
                    ->default(0)
                    ->dehydrated()
                    ->reactive()
                    ->afterStateHydrated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set)
                    )
                    ->afterStateUpdated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set)
                    ),

                // Preço do Produto (sem bonus) — BRL
                Hidden::make('preco_produto_valorizado_rs')
                    ->dehydrated()
                    ->reactive(),

                // Preço do Produto (sem bonus) — USD
                Hidden::make('preco_produto_valorizado_us')
                    ->dehydrated()
                    ->reactive(),

                // Preço do Produto (com bonus) — BRL
                Hidden::make('snap_produto_preco_rs')
                    ->dehydrated()
                    ->reactive(),

                // Preço do Produto (com bonus) — USD
                Hidden::make('snap_produto_preco_us')
                    ->dehydrated()
                    ->reactive(),

                // Custo do Produto — BRL
                Hidden::make('snap_produto_custo_rs')
                    ->dehydrated()
                    ->reactive(),

                // Custo do Produto — USD
                Hidden::make('snap_produto_custo_us')
                    ->dehydrated()
                    ->reactive(),

                // Data Atualização dos Preços do Produto
                Hidden::make('data_atualizacao_snap_precos_produtos')
                    ->default(fn(): \DateTime => now())
                    ->dehydrated(),

                // Valor Total na Negociação — BRL
                Hidden::make('preco_total_produto_negociacao_rs')
                    ->dehydrated()
                    ->reactive(),

                // Valor Total na Negociação — USD
                Hidden::make('preco_total_produto_negociacao_us')
                    ->dehydrated()
                    ->reactive(),

                // Custo Total na Negociação — BRL
                Hidden::make('custo_total_produto_negociacao_rs')
                    ->dehydrated()
                    ->reactive(),

                // Custo Total na Negociação — USD
                Hidden::make('custo_total_produto_negociacao_us')
                    ->dehydrated()
                    ->reactive(),

                // Margem Faturamento — BRL
                Hidden::make('margem_faturamento_rs')
                    ->dehydrated()
                    ->reactive(),

                // Margem Faturamento — USD
                Hidden::make('margem_faturamento_us')
                    ->dehydrated()
                    ->reactive(),

                // Margem Percentual — BRL
                Hidden::make('margem_percentual_rs')
                    ->dehydrated()
                    ->reactive(),

                // Margem Percentual — USD
                Hidden::make('margem_percentual_us')
                    ->dehydrated()
                    ->reactive(),
            ]);
    }
}
