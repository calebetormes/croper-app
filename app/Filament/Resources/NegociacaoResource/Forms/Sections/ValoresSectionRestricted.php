<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Moeda;


class ValoresSectionRestricted
{
    public static function make(): Section
    {
        return Section::make('Valores — Restritos')
            ->columns(4)
            ->hidden(fn() => in_array(Auth::user()->role_id, [1, 2]))
            ->schema([

                TextInput::make('valor_total_pedido_rs')
                    ->label('Valor Total R$ com bonus')
                    ->numeric()
                    ->prefix('R$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == \App\Models\Moeda::where('sigla', 'BRL')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated()
                    ->hidden(fn() => in_array(Auth::user()->role_id, [1, 2])),

                TextInput::make('valor_total_pedido_us')
                    ->label('Valor Total U$ com bonus')
                    ->numeric()
                    ->prefix('US$')
                    ->visible(function ($get) {
                        return $get('moeda_id') == \App\Models\Moeda::where('sigla', 'USS')->value('id');
                    })
                    ->reactive()
                    ->disabled()
                    ->dehydrated()
                    ->hidden(fn() => in_array(Auth::user()->role_id, [1, 2])),

                TextInput::make('indice_valorizacao_saca')
                    ->label('Índice Valorização (saca)')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $rawIndice = $get('indice_valorizacao_saca') ?? '0';
                        $rawPrecoSaca = $get('preco_liquido_saca') ?? '0';
                        $indice = floatval(str_replace(',', '.', $rawIndice));
                        $precoSaca = floatval(str_replace(',', '.', $rawPrecoSaca));
                        $precoVal = $precoSaca * (1 + $indice);

                        $set('preco_liquido_saca_valorizado', round($precoVal, 2));

                        $totalRs = $get('valor_total_pedido_rs') ?? 0;
                        $totalRsVal = $get('valor_total_pedido_rs_valorizado') ?? 0;
                        $totalUs = $get('valor_total_pedido_us') ?? 0;
                        $totalUsVal = $get('valor_total_pedido_us_valorizado') ?? 0;
                        $sigla = optional(Moeda::find($get('moeda_id')))->sigla;
                        $bonus = strtoupper($sigla) === 'USD'
                            ? $totalUsVal - $totalUs
                            : $totalRsVal - $totalRs;

                        $set('bonus_cliente_pacote', round($bonus, 2));
                    }),


                TextInput::make('preco_liquido_saca')
                    ->label('Preço Líquido (saca) sem bonus')
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

                TextInput::make('margem_faturamento_total_rs')
                    ->label('Margem Faturamento (R$)')
                    ->prefix('R$')
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id');
                    }),

                TextInput::make('margem_faturamento_total_us')
                    ->label('Margem Faturamento (US$)')
                    ->prefix('US$')
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'USS')->value('id');
                    }),

                TextInput::make('margem_percentual_total_rs')
                    ->label('Margem Percentual (R$)')
                    ->suffix('%')
                    ->numeric()
                    ->dehydrated()
                    ->reactive()
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id');
                    })
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $marginRs = (float) $state;
                        $marginUs = (float) ($get('margem_percentual_total_us') ?? 0);
                        $margin = max($marginRs, $marginUs);

                        if ($margin < 20) {
                            $level = 3;
                        } elseif ($margin <= 28) {
                            $level = 2;
                        } else {
                            $level = 1;
                        }

                        $set('nivel_validacao_id', $level);
                    }),

                TextInput::make('margem_percentual_total_us')
                    ->label('Margem Percentual (US$)')
                    ->suffix('%')
                    ->numeric()
                    ->dehydrated()
                    ->reactive()
                    ->visible(function ($get) {
                        return $get('moeda_id') == Moeda::where('sigla', 'USS')->value('id');
                    })
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $marginUs = (float) ($get('margem_percentual_total_us') ?? 0);
                        $marginRs = (float) ($get('margem_percentual_total_rs') ?? 0);
                        $margin = max($marginRs, $marginUs);

                        if ($margin < 20) {
                            $level = 3;
                        } elseif ($margin <= 28) {
                            $level = 2;
                        } else {
                            $level = 1;
                        }

                        $set('nivel_validacao_id', $level);
                    }),

                TextInput::make('nivel_validacao_id')
                    ->label('Nível de Aprovação')
                    ->disabled()
                    ->reactive()
                    ->dehydrated()
                    ->default(3),



            ]);
    }
}
