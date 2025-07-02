<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Forms;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\Produto;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;

class NegociacaoProdutoForm
{
    public static function make(): array
    {
        return [
            Repeater::make('negociacaoProdutos')
                ->relationship('negociacaoProdutos')
                ->reactive() // re-render repeater when moeda_id changes
                ->label('Produtos')
                ->columns(3)
                ->collapsed()
                ->defaultItems(0)
                ->createItemButtonLabel('Adicionar Produto')
                ->reorderable()
                ->grid(1)
                ->itemLabel(
                    fn(array $state): ?string =>
                    Produto::find($state['produto_id'])?->nome_composto
                    ?? 'Novo Produto'
                )
                ->schema([
                    // Exibe a moeda selecionada (1 = BRL, 2 = USD)
                    Placeholder::make('moeda_label')
                        ->label('Moeda Selecionada')
                        ->content(
                            fn(Get $get) =>
                            $get('../../moeda_id') === 1
                            ? 'BRL'
                            : ($get('../../moeda_id') === 2 ? 'USD' : '')
                        )
                        ->reactive(),

                    Section::make('Informações Básicas')
                        ->columns(3)
                        ->schema([
                            Select::make('produto_id')
                                ->label('Produto')
                                ->relationship('produto', 'nome')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->getOptionLabelFromRecordUsing(fn(Produto $r) => $r->nome_composto)
                                ->required()
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::produtoSelectAfterStateUpdated($get, $set)
                                ),

                            TextInput::make('volume')
                                ->label('Volume')
                                ->numeric()
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set)
                                ),

                            TextInput::make('preco_produto_valorizado_rs')
                                ->label('Valorizado RS')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->reactive(),

                            TextInput::make('preco_produto_valorizado_us')
                                ->label('Valorizado US')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->reactive(),
                        ]),

                    Section::make('Detalhes do Produto')
                        ->columns(3)
                        ->schema([
                            TextInput::make('indice_valorizacao')
                                ->label('Índice de Valorização')
                                ->numeric()
                                ->placeholder('0.10 para 10%')
                                ->live()
                                ->default(0)
                                ->required()
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set)
                                ),

                            DatePicker::make('data_atualizacao_snap_precos_produtos')
                                ->label('Data Atualização')
                                ->default(fn(): \DateTime => now())
                                ->disabled()
                                ->dehydrated(true),

                            TextInput::make('snap_produto_preco_rs')
                                ->label('Preço RS')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->reactive(),

                            TextInput::make('snap_produto_preco_us')
                                ->label('Preço US')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->reactive(),

                            TextInput::make('preco_total_produto_negociacao_rs')
                                ->label('Preço Total RS')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->reactive(),

                            TextInput::make('preco_total_produto_negociacao_us')
                                ->label('Preço Total US')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->reactive(),

                            TextInput::make('custo_total_produto_negociacao_rs')
                                ->label('Custo Total RS')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->reactive(),

                            TextInput::make('custo_total_produto_negociacao_us')
                                ->label('Custo Total US')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->reactive(),

                            TextInput::make('margem_faturamento_rs')
                                ->label('Margem Faturamento RS')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->reactive(),

                            TextInput::make('margem_faturamento_us')
                                ->label('Margem Faturamento US')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->reactive(),
                        ]),
                ])
                ->afterStateUpdated(
                    fn(Get $get, Set $set) =>
                    NegociacaoProdutoLogic::repeaterAfterStateUpdated($get, $set)
                ),
        ];
    }
}
