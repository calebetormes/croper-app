<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/ProdutosInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class ProdutosInfolist
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Produtos')
            ->schema([
                RepeatableEntry::make('negociacaoProdutos')
                    ->schema([
                        // Linha principal
                        Grid::make(6)
                            ->schema([
                                TextEntry::make('produto.nome_composto')
                                    ->label('Produto')
                                    ->columnSpan(2),

                                TextEntry::make('volume')
                                    ->label('Volume')
                                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),

                                // Total Preço Negociação
                                TextEntry::make('preco_total_produto_negociacao_rs')
                                    ->label('Valor Total Neg. (R$)')
                                    ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                    ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                TextEntry::make('preco_total_produto_negociacao_us')
                                    ->label('Valor Total Neg. (US$)')
                                    ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                    ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                // Total Custo Negociação
                                TextEntry::make('custo_total_produto_negociacao_rs')
                                    ->label('Custo Total Neg. (R$)')
                                    ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                    ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                TextEntry::make('custo_total_produto_negociacao_us')
                                    ->label('Custo Total Neg. (US$)')
                                    ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                    ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                //Margem de Lucro
                                TextEntry::make('margem_percentual_rs')
                                    ->label('Resultado')
                                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.') . ' %'),
                            ]),

                        // Detalhes colapsáveis
                        InfolistSection::make('Detalhes do Produto')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                Grid::make(4)
                                    ->schema([


                                        // Preço Unitário
                                        TextEntry::make('snap_produto_preco_rs')
                                            ->label('Preço Unit. (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('snap_produto_preco_us')
                                            ->label('Preço Unit. (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Custo Unitário
                                        TextEntry::make('snap_produto_custo_rs')
                                            ->label('Custo Unit. (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('snap_produto_custo_us')
                                            ->label('Custo Unit. (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),


                                        // Margem de faturamento
                                        TextEntry::make('margem_faturamento_rs')
                                            ->label('Margem Faturamento (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('margem_faturamento_us')
                                            ->label('Margem Faturamento (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Data de atualização
                                        TextEntry::make('data_atualizacao_snap_precos_produtos')
                                            ->label('Data Atualização')
                                            ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
                                    ]),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }
}
