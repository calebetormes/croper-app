<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use App\Models\Produto;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ProdutosSectionForm
{
    public static function make(): Section
    {
        return Section::make('Produtos')
            ->schema([
                Repeater::make('negociacaoProdutos')
                    ->relationship('negociacaoProdutos')
                    ->itemLabel(fn ($record): string => $record->produto?->nome_composto ?? 'Novo item')

                    ->label('Produtos')
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->options(fn () => Produto::all()->pluck('nome_composto', 'id')->toArray())
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (int|string $state, Get $get, Set $set) {
                                $produto = Produto::find($state);

                                $set('snap_produto_preco_real_rs', $produto?->preco_real_rs);
                                $set('snap_produto_preco_real_us', $produto?->preco_real_us);
                                $set('snap_produto_preco_virtual_rs', $produto?->preco_virtual_rs);
                                $set('snap_produto_preco_virtual_us', $produto?->preco_virtual_us);

                                // $set('data_atualizacao_snap_precos_produtos', now());
                            }),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),

                        TextInput::make('potencial_produto')
                            ->label('Potencial')
                            ->numeric()
                            ->required(),

                        TextInput::make('dose_hectare')
                            ->label('Dose (ha)')
                            ->numeric()
                            ->required(),

                        TextInput::make('snap_produto_preco_real_rs')
                            ->label('Preço Real (R$)')
                            ->numeric(),

                        TextInput::make('snap_produto_preco_real_us')
                            ->label('Preço Real (US$)')
                            ->numeric(),

                        TextInput::make('snap_produto_preco_virtual_rs')
                            ->label('Preço Virtual (R$)')
                            ->numeric(),

                        TextInput::make('snap_produto_preco_virtual_us')
                            ->label('Preço Virtual (US$)')
                            ->numeric(),

                        Toggle::make('snap_precos_fixados')
                            ->label('Atualizar Preços')
                            ->inline()
                            ->reactive()
                            ->afterStateUpdated(function (bool $state, Get $get, Set $set) {
                                if (! $state) {
                                    return;
                                }

                                // Pega o produto selecionado no mesmo repeater
                                $produtoId = $get('produto_id');
                                $produto = Produto::find($produtoId);

                                if ($produto) {
                                    $set('snap_produto_preco_real_rs', $produto->preco_real_rs);
                                    $set('snap_produto_preco_real_us', $produto->preco_real_us);
                                    $set('snap_produto_preco_virtual_rs', $produto->preco_virtual_rs);
                                    $set('snap_produto_preco_virtual_us', $produto->preco_virtual_us);
                                    $set('snap_produto_preco_virtual_us', $produto?->preco_virtual_us);
                                    // $set('data_atualizacao_snap_precos_produtos', now()->toDateTimeString());
                                }

                                // “Reseta” o toggle pra deixar pronto pra próxima atualização
                                $set('snap_precos_fixados', false);
                            }),

                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data de Fixação dos Preços'),
                    ])
                    ->columns(5)
                    ->columnSpan('full'),
            ]);
    }
}
