<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Moeda;
use Illuminate\Support\Facades\Auth;

class ValoresSectionVendedor
{
    public static function make(): Section
    {
        return Section::make('')
            ->schema([

                TextInput::make('valor_total_pedido_rs_valorizado')
                    ->label('Valor Total R$')
                    ->numeric()
                    ->prefix('R$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_us_valorizado')
                    ->label('Valor Total U$')
                    ->numeric()
                    ->prefix('US$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'USS')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('investimento_total_sacas')
                    ->label('Investimento Total (sacas)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('investimento_sacas_hectare')
                    ->label('Investimento (sacas/ha)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('preco_liquido_saca_valorizado')
                    ->label('Preço Líquido (saca)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),


                TextInput::make('peso_total_kg')
                    ->label('Peso Total (kg)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),
            ])
            ->columns(5);
    }
}
