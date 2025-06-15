<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                TextInput::make('valor_total_pedido_rs')
                    ->label('Valor Total R$')
                    ->numeric(),

                TextInput::make('valor_total_pedido_us')
                    ->label('Valor Total U$')
                    ->numeric(),

                TextInput::make('valor_total_pedido_rs_valorizado')
                    ->label('Valor Total R$ Valorizado')
                    ->numeric(),

                TextInput::make('valor_total_pedido_us_valorizado')
                    ->label('Valor Total U$ Valorizado')
                    ->numeric(),

                TextInput::make('investimento_total_sacas')
                    ->label('Investimento Total (sacas)')
                    ->numeric(),

                TextInput::make('investimento_sacas_hectare')
                    ->label('Investimento (sacas/ha)')
                    ->numeric(),

                TextInput::make('indice_valorizacao_saca')
                    ->label('Índice Valorização (saca)')
                    ->numeric(),

                TextInput::make('preco_liquido_saca')
                    ->label('Preço Líquido (saca)')
                    ->numeric(),

                TextInput::make('preco_liquido_saca_valorizado')
                    ->label('Preço Líquido Valorizado (saca)')
                    ->numeric(),

                TextInput::make('bonus_cliente_pacote')
                    ->label('Bônus Cliente Pacote')
                    ->numeric(),

                TextInput::make('cotacao_moeda_usd_brl')
                    ->label('Cotação USD/BRL')
                    ->numeric()
                    ->reactive(),

                TextInput::make('peso_total_kg')
                    ->label('Peso Total (kg)')
                    ->numeric(),
            ])
            ->columns(4);
    }
}
