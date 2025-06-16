<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Moeda;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                TextInput::make('valor_total_pedido_rs')
                    ->label('Valor Total R$ com bonus')
                    ->numeric()
                    ->prefix('R$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_us')
                    ->label('Valor Total U$ com bonus')
                    ->numeric()
                    ->prefix('US$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'USS')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_rs_valorizado')
                    ->label('Valor Total R$ sem bonus')
                    ->numeric()
                    ->prefix('R$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('valor_total_pedido_us_valorizado')
                    ->label('Valor Total U$ sem bonus')
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

                        // 3) Bônus Cliente Pacote
                        // Busca totais já calculados no form
                        $totalRs = $get('valor_total_pedido_rs') ?? 0;
                        $totalRsVal = $get('valor_total_pedido_rs_valorizado') ?? 0;
                        $totalUs = $get('valor_total_pedido_us') ?? 0;
                        $totalUsVal = $get('valor_total_pedido_us_valorizado') ?? 0;
                        $rawMoedaId = $get('moeda_id') ?? null;

                        // Descobre sigla
                        $sigla = optional(Moeda::find($rawMoedaId))->sigla;
                        if (strtoupper($sigla) === 'USD') {
                            $bonus = $totalUsVal - $totalUs;
                        } else {
                            $bonus = $totalRsVal - $totalRs;
                        }

                        $set('bonus_cliente_pacote', round($bonus, 2));
                    }),


                TextInput::make('preco_liquido_saca')
                    ->label('Preço Líquido (saca) sem bonus')
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


                TextInput::make('bonus_cliente_pacote')
                    ->label('Bônus do Cliente no Pacote')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('cotacao_moeda_usd_brl')
                    ->label('Cotação USD/BRL')
                    ->numeric()
                    ->hidden()
                    ->default(0)
                    ->dehydrated(),

                TextInput::make('peso_total_kg')
                    ->label('Peso Total (kg)')
                    ->numeric()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),
            ])
            ->columns(4);
    }
}
