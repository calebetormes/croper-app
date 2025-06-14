<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Produto;

class NegociacaoProdutosSectionForm
{
    public static function make(): Section
    {
        return Section::make('Produtos')
            ->schema([
                Repeater::make('negociacaoProdutos')
                    ->relationship('negociacaoProdutos')
                    ->label('Produtos')
                    ->columns(4)
                    ->collapsible()
                    ->createItemButtonLabel('Adicionar Produto')
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->relationship('produto', 'nome') // usa a coluna real para evitar erro
                            ->getOptionLabelFromRecordUsing(
                                fn(Produto $record): string => $record->nome_composto
                            )
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $produto = Produto::find($get('produto_id'));
                                if ($produto) {
                                    // snapshot do produto
                                    $set('snap_produto_preco_rs', $produto->preco_rs);
                                    $set('snap_produto_preco_us', $produto->preco_us);
                                    // data atualização só na criação
                                    if (!$get('data_atualizacao_snap_precos_produtos')) {
                                        $set('data_atualizacao_snap_precos_produtos', now());
                                    }
                                }
                            }),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),

                        TextInput::make('indice_valorizacao')
                            ->label('Índice de Valorização')
                            ->numeric()
                            ->placeholder('0.10 para 10%')
                            ->live()
                            ->default(0)
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $snapRs = $get('snap_produto_preco_rs') ?? 0;
                                $snapUs = $get('snap_produto_preco_us') ?? 0;
                                $indice = $get('indice_valorizacao') ?? 0;
                                $set('preco_produto_valorizado_rs', $snapRs * (1 + $indice));
                                $set('preco_produto_valorizado_us', $snapUs * (1 + $indice));
                            }),

                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data Atualização')
                            ->default(fn(): \DateTime => now())
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('snap_produto_preco_rs')
                            ->label('Preço RS')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('snap_produto_preco_us')
                            ->label('Preço US')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('preco_produto_valorizado_rs')
                            ->label('Valorizado RS')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('preco_produto_valorizado_us')
                            ->label('Valorizado US')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),


                    ]),
            ]);
    }
}
