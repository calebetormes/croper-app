<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use App\Models\Negociacao;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                Placeholder::make('valor_total_pedido_rs')
                    ->label('Valor Total R$')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        'R$ ' . number_format(
                            $record->negociacaoProdutos->sum(
                                fn($item) =>
                                $item->quantidade * $item->preco_liquido_saca_valorizado
                            ),
                            2,
                            ',',
                            '.'
                        )
                    ),

                Placeholder::make('valor_total_pedido_us')
                    ->label('Valor Total U$')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        '$ ' . number_format(
                            ($record->negociacaoProdutos->sum(
                                fn($item) =>
                                $item->quantidade * $item->preco_liquido_saca_valorizado
                            )
                            )
                            / max($record->cotacao_moeda_usd_brl ?: 1, 1),
                            2,
                            ',',
                            '.'
                        )
                    ),

                Placeholder::make('valor_total_pedido_rs_valorizado')
                    ->label('Valor Total R$ Valorizado')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        'R$ ' . number_format(
                            $record->negociacaoProdutos->sum(
                                fn($item) =>
                                $item->total_preco_valorizado_rs
                            ),
                            2,
                            ',',
                            '.'
                        )
                    ),

                Placeholder::make('valor_total_pedido_us_valorizado')
                    ->label('Valor Total U$ Valorizado')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        '$ ' . number_format(
                            ($record->negociacaoProdutos->sum(
                                fn($item) =>
                                $item->total_preco_valorizado_rs
                            )
                            )
                            / max($record->cotacao_moeda_usd_brl ?: 1, 1),
                            2,
                            ',',
                            '.'
                        )
                    ),

                Placeholder::make('investimento_total_sacas')
                    ->label('Investimento Total (sacas)')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        number_format(
                            $record->preco_liquido_saca > 0
                            ? ($record->negociacaoProdutos->sum(
                                fn($item) =>
                                $item->quantidade * $item->preco_liquido_saca
                            )
                                / $record->preco_liquido_saca
                            )
                            : 0,
                            2,
                            ',',
                            '.'
                        )
                    ),

                TextInput::make('bonus_cliente_pacote')
                    ->label('Bônus Cliente Pacote')
                    ->numeric(),

                TextInput::make('cotacao_moeda_usd_brl')
                    ->label('Cotação USD/BRL')
                    ->numeric()
                    ->reactive(),

                Placeholder::make('peso_total_kg')
                    ->label('Peso Total (kg)')
                    ->reactive()
                    ->content(
                        fn(Negociacao $record): string =>
                        (string) $record->negociacaoProdutos->sum(
                            fn($item) =>
                            $item->quantidade * $item->peso_liquido_kg
                        )
                    ),
            ])
            ->columns(2);
    }
}
