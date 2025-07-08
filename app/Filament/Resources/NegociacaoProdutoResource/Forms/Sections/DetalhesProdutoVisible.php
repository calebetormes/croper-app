<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;

class DetalhesProdutoVisible
{
    public static function section(): Section
    {
        return Section::make('Detalhes do Produto')
            ->columns(3)
            ->schema([
                TextInput::make('indice_valorizacao')
                    ->label('Índice de Valorização')
                    ->numeric()
                    ->placeholder('0.10 para 10%')
                    ->live()
                    ->default(0)
                    ->required()
                    ->dehydrated()
                    ->afterStateHydrated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set)
                    )
                    ->afterStateUpdated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set)
                    ),

                TextInput::make('snap_produto_preco_rs')
                    ->label('Preço do Produto')
                    ->prefix('BRL')
                    ->numeric()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive()
                    ->afterStateUpdated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set)
                    ),

                TextInput::make('snap_produto_preco_us')
                    ->label('Preço do Produto Valorizado')
                    ->prefix('USS')
                    ->numeric()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive()
                    ->afterStateUpdated(
                        fn(Get $get, Set $set) =>
                        NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set)
                    ),

                TextInput::make('preco_produto_valorizado_rs')
                    ->label('Preço do Produto Valorizado')
                    ->prefix('BRL')
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('preco_produto_valorizado_us')
                    ->label('Preço do Produto (sem bonus)')
                    ->prefix('USS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),

                TextInput::make('snap_produto_custo_rs')
                    ->label('Custo do Produto')
                    ->prefix('BRL')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('snap_produto_custo_us')
                    ->label('Custo do Produto')
                    ->prefix('USS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),

                DatePicker::make('data_atualizacao_snap_precos_produtos')
                    ->label('Data Atualização dos Preços do Produto')
                    ->default(fn(): \DateTime => now())
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('preco_total_produto_negociacao_rs')
                    ->label('Valor Total na Negociação')
                    ->prefix('BRL')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('preco_total_produto_negociacao_us')
                    ->label('Valor Total na Negociação')
                    ->prefix('USS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),

                TextInput::make('custo_total_produto_negociacao_rs')
                    ->label('Custo Total na Negociação')
                    ->prefix('BRL')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('custo_total_produto_negociacao_us')
                    ->label('Custo Total na Negociação')
                    ->prefix('USS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),

                TextInput::make('margem_faturamento_rs')
                    ->label('Margem Faturamento')
                    ->prefix('BRL')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('margem_faturamento_us')
                    ->label('Margem Faturamento')
                    ->prefix('USS')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),

                TextInput::make('margem_percentual_rs')
                    ->label('Margem Percentual')
                    ->suffix('%')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                    ->reactive(),

                TextInput::make('margem_percentual_us')
                    ->label('Margem Percentual')
                    ->suffix('%')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                    ->reactive(),
            ]);
    }
}
