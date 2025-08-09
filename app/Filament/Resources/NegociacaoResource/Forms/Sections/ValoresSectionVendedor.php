<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use App\Models\Moeda;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;

class ValoresSectionVendedor
{
    public static function make(): Section
    {
        return Section::make('')
            ->schema([

                // --- Valor Total U$ (exibição) ---
                Placeholder::make('valor_total_pedido_us_valorizado_fmt')
                    ->label('Valor Total U$')
                    ->content(
                        fn(Get $get) =>
                        'US$ ' . number_format(
                            (float) ($get('valor_total_pedido_us_valorizado') ?? 0),
                            2,
                            '.',
                            ','
                        )
                    )
                    ->visible(
                        fn(Get $get) =>
                        $get('moeda_id') == Moeda::where('sigla', 'USS')->value('id') // mantenho sua condição
                    )
                    ->live(),

                // --- Valor Total U$ (persistência oculta) ---
                Hidden::make('valor_total_pedido_us_valorizado')
                    ->dehydrated(),

                // --- Investimento Total (sacas) ---
                Placeholder::make('investimento_total_sacas_fmt')
                    ->label('Investimento Total (sacas)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('investimento_total_sacas') ?? 0), 0, ',', '.')
                    )
                    ->live(),
                Hidden::make('investimento_total_sacas')->dehydrated(),

                // --- Investimento (sacas/ha) ---
                Placeholder::make('investimento_sacas_hectare_fmt')
                    ->label('Investimento (sacas/ha)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('investimento_sacas_hectare') ?? 0), 2, ',', '.')
                    )
                    ->live(),
                Hidden::make('investimento_sacas_hectare')->dehydrated(),

                // --- Preço Líquido (saca) ---
                Placeholder::make('preco_liquido_saca_valorizado_fmt')
                    ->label('Preço Líquido (saca)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('preco_liquido_saca_valorizado') ?? 0), 2, ',', '.')
                    )
                    ->live(),
                Hidden::make('preco_liquido_saca_valorizado')->dehydrated(),

                // --- Peso Total (kg) ---
                Placeholder::make('peso_total_kg_fmt')
                    ->label('Peso Total (kg)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('peso_total_kg') ?? 0), 2, ',', '.')
                    )
                    ->live(),
                Hidden::make('peso_total_kg')->dehydrated(),

            ])
            ->columns(5);
    }
}
