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
            ]);
    }
}
