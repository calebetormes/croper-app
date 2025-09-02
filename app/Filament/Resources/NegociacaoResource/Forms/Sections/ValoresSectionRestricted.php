<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput; // mantém para campos que continuam editáveis
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Moeda;
use Illuminate\Support\Facades\Auth;

class ValoresSectionRestricted
{
    public static function make(): Section
    {
        // Regra de visibilidade para itens "restritos" (apenas para UI/editáveis)
        $canSeeRestricted = fn() => !in_array(Auth::user()->role_id, [1, 2]);

        return Section::make('Valores — Restritos')
            ->columns(4)
            // ->hidden(...) REMOVIDO
            ->schema([

                // --- Valor Total R$ (valorizado) ---
                Placeholder::make('valor_total_pedido_rs_valorizado_fmt')
                    ->label('Valor Total R$')
                    ->content(
                        fn(Get $get) =>
                        'R$ ' . number_format((float) ($get('valor_total_pedido_rs_valorizado') ?? 0), 2, ',', '.')
                    )
                    ->live()
                    ->visible($canSeeRestricted),

                Hidden::make('valor_total_pedido_rs_valorizado')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Valor Total R$ com bônus (exibição) ---
                Placeholder::make('valor_total_pedido_rs_fmt')
                    ->label('Valor Total R$ com bônus')
                    ->content(
                        fn(Get $get) =>
                        'R$ ' . number_format((float) ($get('valor_total_pedido_rs') ?? 0), 2, ',', '.')
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id')
                    )
                    ->live(),

                Hidden::make('valor_total_pedido_rs')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Valor Total U$ com bônus (exibição) ---
                Placeholder::make('valor_total_pedido_us_fmt')
                    ->label('Valor Total U$ com bônus')
                    ->content(
                        fn(Get $get) =>
                        'US$ ' . number_format((float) ($get('valor_total_pedido_us') ?? 0), 2, '.', ',')
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'USD')->value('id')
                    )
                    ->live(),

                Hidden::make('valor_total_pedido_us')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Índice Valorização (saca) (continua editável) ---
                TextInput::make('indice_valorizacao_saca')
                    ->label('Índice Valorização (saca)')
                    ->numeric()
                    ->reactive()
                    ->visible($canSeeRestricted) // só quem pode ver a seção restrita enxerga/edita
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $rawIndice = $get('indice_valorizacao_saca') ?? '0';
                        $rawPrecoSaca = $get('preco_liquido_saca') ?? '0';

                        $indice = (float) str_replace(',', '.', $rawIndice);
                        // se vier em %, converte p/ fator
                        $indiceF = $indice > 1 ? $indice / 100 : $indice;
                        $precoSaca = (float) str_replace(',', '.', $rawPrecoSaca);

                        $set('preco_liquido_saca_valorizado', round($precoSaca * (1 + $indiceF), 2));

                        $totalRs = (float) ($get('valor_total_pedido_rs') ?? 0);
                        $totalRsV = (float) ($get('valor_total_pedido_rs_valorizado') ?? 0);
                        $totalUs = (float) ($get('valor_total_pedido_us') ?? 0);
                        $totalUsV = (float) ($get('valor_total_pedido_us_valorizado') ?? 0);
                        $sigla = optional(Moeda::find($get('moeda_id')))->sigla;

                        $bonus = strtoupper((string) $sigla) === 'USD'
                            ? ($totalUsV - $totalUs)
                            : ($totalRsV - $totalRs);

                        $set('bonus_cliente_pacote', round($bonus, 2));
                    }),

                // --- Preço Líquido (saca) sem bônus ---
                Placeholder::make('preco_liquido_saca_fmt')
                    ->label('Preço Líquido (saca) sem bônus')
                    ->content(
                        fn(Get $get) =>
                        'R$ ' . number_format((float) ($get('preco_liquido_saca') ?? 0), 2, ',', '.')
                    )
                    ->live()
                    ->visible($canSeeRestricted),

                Hidden::make('preco_liquido_saca')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Bônus do Cliente no Pacote ---
                Placeholder::make('bonus_cliente_pacote_fmt')
                    ->label('Bônus do Cliente no Pacote')
                    ->content(
                        fn(Get $get) =>
                        'R$ ' . number_format((float) ($get('bonus_cliente_pacote') ?? 0), 2, ',', '.')
                    )
                    ->live()
                    ->visible($canSeeRestricted),

                Hidden::make('bonus_cliente_pacote')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Cotação USD/BRL (permanece oculto/cru) ---
                Hidden::make('cotacao_moeda_usd_brl')
                    ->default(0)
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Margem Faturamento (R$) ---
                Placeholder::make('margem_faturamento_total_rs_fmt')
                    ->label('Margem Faturamento (R$)')
                    ->content(
                        fn(Get $get) =>
                        'R$ ' . number_format((float) ($get('margem_faturamento_total_rs') ?? 0), 2, ',', '.')
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id')
                    )
                    ->live(),

                Hidden::make('margem_faturamento_total_rs')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Margem Faturamento (US$) ---
                Placeholder::make('margem_faturamento_total_us_fmt')
                    ->label('Margem Faturamento (US$)')
                    ->content(
                        fn(Get $get) =>
                        'US$ ' . number_format((float) ($get('margem_faturamento_total_us') ?? 0), 2, '.', ',')
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'USD')->value('id')
                    )
                    ->live(),

                Hidden::make('margem_faturamento_total_us')
                    ->dehydrated()
                    ->dehydratedWhenHidden(),

                // --- Margem Percentual (R$) ---
                Placeholder::make('margem_percentual_total_rs_fmt')
                    ->label('Margem Percentual (R$)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('margem_percentual_total_rs') ?? 0), 2, ',', '.') . ' %'
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'BRL')->value('id')
                    )
                    ->live(),

                Hidden::make('margem_percentual_total_rs')
                    ->dehydrated()
                    ->dehydratedWhenHidden()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $marginRs = (float) $state;
                        $marginUs = (float) ($get('margem_percentual_total_us') ?? 0);
                        $margin = max($marginRs, $marginUs);

                        $level = $margin < 20 ? 3 : ($margin <= 28 ? 2 : 1);
                        $set('nivel_validacao_id', $level);
                    }),

                // --- Margem Percentual (US$) ---
                Placeholder::make('margem_percentual_total_us_fmt')
                    ->label('Margem Percentual (US$)')
                    ->content(
                        fn(Get $get) =>
                        number_format((float) ($get('margem_percentual_total_us') ?? 0), 2, ',', '.') . ' %'
                    )
                    ->visible(
                        fn(Get $get) =>
                        $canSeeRestricted()
                        && $get('moeda_id') == Moeda::where('sigla', 'USD')->value('id')
                    )
                    ->live(),

                Hidden::make('margem_percentual_total_us')
                    ->dehydrated()
                    ->dehydratedWhenHidden()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        $marginUs = (float) $state;
                        $marginRs = (float) ($get('margem_percentual_total_rs') ?? 0);
                        $margin = max($marginRs, $marginUs);

                        $level = $margin < 20 ? 3 : ($margin <= 28 ? 2 : 1);
                        $set('nivel_validacao_id', $level);
                    }),

                // --- Nível de Aprovação ---
                Placeholder::make('nivel_validacao_id_fmt')
                    ->label('Nível de Aprovação')
                    ->content(fn(Get $get) => (string) ($get('nivel_validacao_id') ?? 3))
                    ->live()
                    ->visible($canSeeRestricted),

                Hidden::make('nivel_validacao_id')
                    ->default(3)
                    ->dehydrated()
                    ->dehydratedWhenHidden(),
            ]);
    }
}
