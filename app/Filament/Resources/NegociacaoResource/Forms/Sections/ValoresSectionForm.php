<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                TextInput::make('valor_total_pedido_rs')
                    ->label('Valor Total R$')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_us')
                    ->label('Valor Total U$')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_rs_valorizado')
                    ->label('Valor Total R$ Valorizado')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_us_valorizado')
                    ->label('Valor Total U$ Valorizado')
                    ->numeric()
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
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('indice_valorizacao_saca')
                    ->label('Índice Valorização (saca)')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        // raw do índice e do preço da saca
                        $rawIndice = $get('indice_valorizacao_saca') ?? '0';
                        $rawPrecoSaca = $get('preco_liquido_saca') ?? '0';

                        // normaliza vírgula → ponto e cast float
                        $indice = floatval(str_replace(',', '.', $rawIndice));
                        $precoSaca = floatval(str_replace(',', '.', $rawPrecoSaca));

                        // recalcula o preço valorizado
                        $precoValorizado = $precoSaca * (1 + $indice);

                        // atualiza o campo no form
                        $set('preco_liquido_saca_valorizado', round($precoValorizado, 2));
                    }),

                TextInput::make('preco_liquido_saca')
                    ->label('Preço Líquido (saca)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('preco_liquido_saca_valorizado')
                    ->label('Preço Líquido Valorizado (saca)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),


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
