<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/ProdutosInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class ProdutosInfolistTodosCampos
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Produtos')
            ->schema([
                RepeatableEntry::make('negociacaoProdutos')
                    ->schema([
                        // Linha principal
                        Grid::make(5)
                            ->schema([
                                TextEntry::make('produto.nome_composto')
                                    ->label('Produto')
                                    ->columnSpan(3),

                                TextEntry::make('volume')
                                    ->label('Volume')
                                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),

                                TextEntry::make('margem_percentual_rs')
                                    ->label('Margem (%)')
                                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.') . ' %'),
                            ]),

                        // Detalhes colapsáveis
                        InfolistSection::make('Detalhes do Produto')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('indice_valorizacao')
                                            ->label('Índice Valorização'),

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

                                        // Total Preço Negociação
                                        TextEntry::make('preco_total_produto_negociacao_rs')
                                            ->label('Total Preço Neg. (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('preco_total_produto_negociacao_us')
                                            ->label('Total Preço Neg. (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Total Custo Negociação
                                        TextEntry::make('custo_total_produto_negociacao_rs')
                                            ->label('Total Custo Neg. (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('custo_total_produto_negociacao_us')
                                            ->label('Total Custo Neg. (US$)')
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

                                        // Preço Valorizado
                                        TextEntry::make('preco_produto_valorizado_rs')
                                            ->label('Preço Valorizado (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('preco_produto_valorizado_us')
                                            ->label('Preço Valorizado (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Total
                                        TextEntry::make('total_preco_rs')
                                            ->label('Total (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('total_preco_us')
                                            ->label('Total (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Total Valorizado
                                        TextEntry::make('total_preco_valorizado_rs')
                                            ->label('Total Valorizado (R$)')
                                            ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id === 1),

                                        TextEntry::make('total_preco_valorizado_us')
                                            ->label('Total Valorizado (US$)')
                                            ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.'))
                                            ->visible(fn($state, $record) => $record->negociacao->moeda_id !== 1),

                                        // Preço Líquido por saca
                                        TextEntry::make('preco_liquido_saca')
                                            ->label('Preço Líquido (saca)')
                                            ->formatStateUsing(
                                                fn($state, $record) =>
                                                $record->negociacao->moeda_id === 1
                                                ? 'R$ ' . number_format($state, 2, ',', '.')
                                                : 'US$ ' . number_format($state, 2, ',', '.')
                                            ),

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
