<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use App\Models\Produto;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProdutosSectionForm
{
    public static function make(): Section
    {

        return Section::make('Produtos')
            ->schema([
                Repeater::make('negociacaoProdutos')
                    ->label('Produtos')
                    ->default([])
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->options(Produto::all()->pluck('nome_composto', 'id'))
                            ->searchable()
                            ->required(),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),

                        TextInput::make('potencial_produto')
                            ->label('Potencial Produto')
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

                        TextInput::make('snap_precos_fixados')
                            ->label('Preços Fixados')
                            ->numeric(),

                        TextInput::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data Atualização')
                            ->default(now()->toDateString()),
                    ])
                    ->columns(2),

                // 1) MultiSelect para adicionar/remover produtos
                /*
                MultiSelect::make('selected_products')
                    ->label('Adicionar Produtos')
                    ->options(fn () => Produto::all()->pluck('nome_composto', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Monta a lista de itens com produto_id
                        $items = collect($state)->map(fn ($id) => [
                            'produto_id' => $id,
                            'volume' => null,
                            'potencial_produto' => null,
                            'dose_hectare' => null,
                            'snap_produto_preco_real_rs' => null,
                            'snap_produto_preco_real_us' => null,
                            'snap_produto_preco_virtual_rs' => null,
                            'snap_produto_preco_virtual_us' => null,
                            'snap_precos_fixados' => false,
                            'data_atualizacao_snap_precos_produtos' => now()->toDateString(),
                        ])->toArray();

                        // Note o statePath aqui: snake_case
                        $set('negociacao_produtos', $items);
                    })
                    ->columnSpan(2),
                /*
                Repeater::make('negociacao_produtos')
                    ->relationship('negociacaoProdutos')
                    ->statePath('negociacao_produtos')
                    ->schema([
                        // *** Campo Hidden para garantir que produto_id venha no payload ***
                        Hidden::make('produto_id')
                            ->required(),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),

                        TextInput::make('potencial_produto')
                            ->label('Potencial')
                            ->numeric(),

                        TextInput::make('dose_hectare')
                            ->label('Dose (ha)')
                            ->numeric(),

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
                            ->label('Preços fixados'),

                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data de Atualização'),
                    ])
                    ->columns(2),

                // 2) Repeater para editar os detalhes de cada produto
                /*
                Repeater::make('negociacaoProdutos')
                    ->relationship('negociacaoProdutos')
                    ->statePath('negociacaoProdutos')
                    ->schema([
                        TextInput::make('produto_id')
                            ->label('Produto ID')
                            ->disabled(),
                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),
                        TextInput::make('potencial_produto')
                            ->label('Potencial')
                            ->numeric(),
                        TextInput::make('dose_hectare')
                            ->label('Dose (ha)')
                            ->numeric(),
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
                            ->label('Preços fixados'),
                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data de Atualização'),
                    ])
                    ->columns(2),
                Repeater::make('negociacao_produtos')
                    ->relationship('negociacaoProdutos')
                    ->statePath('negociacao_produtos')
                    ->label('Produtos')
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->options(fn () => Produto::all()->pluck('nome_composto', 'id')->toArray())
                            ->searchable()
                            ->required(),

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
                            ->numeric()
                            ->required(),

                        TextInput::make('snap_produto_preco_real_us')
                            ->label('Preço Real (US$)')
                            ->numeric()
                            ->required(),

                        TextInput::make('snap_produto_preco_virtual_rs')
                            ->label('Preço Virtual (R$)')
                            ->numeric()
                            ->required(),

                        TextInput::make('snap_produto_preco_virtual_us')
                            ->label('Preço Virtual (US$)')
                            ->numeric()
                            ->required(),

                        Toggle::make('snap_precos_fixados')
                            ->label('Preços fixados')
                            ->default(false),

                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data de Atualização')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(4),
                    */
            ]);
    }
}
