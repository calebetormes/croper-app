<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Actions\ModalAction;

class InfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        $record = $infolist->getRecord();

        return $infolist
            ->schema([
                // Seção Geral
                Section::make('Geral')
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('pedido_id')
                                ->label('Pedido ID'),
                            TextEntry::make('data_negocio')
                                ->label('Data da Negociação')
                                ->date(),
                            TextEntry::make('cliente')
                                ->label('Cliente'),
                            TextEntry::make('cultura.nome')
                                ->label('Cultura'),
                            TextEntry::make('pracaCotacao.nome')
                                ->label('Praça de Cotação'),
                            TextEntry::make('pagamento.nome')
                                ->label('Vencimento'),
                            TextEntry::make('moeda.sigla')
                                ->label('Moeda'),

                            TextEntry::make('snap_praca_cotacao_preco')
                                ->label('Preço Snapshot (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('snap_praca_cotacao_preco')
                                ->label('Preço Snapshot (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),

                            TextEntry::make('area_hectares')
                                ->label('Área (ha)'),
                            TextEntry::make('peso_total_kg')
                                ->label('Peso Total (kg)'),

                            TextEntry::make('valor_total_pedido_rs')
                                ->label('Total Pedido (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('valor_total_pedido_us')
                                ->label('Total Pedido (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),

                            TextEntry::make('preco_liquido_saca')
                                ->label('Preço Líquido saca (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('preco_liquido_saca')
                                ->label('Preço Líquido saca (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),

                            TextEntry::make('statusNegociacao.nome')
                                ->label('Status Negociação'),
                            TextEntry::make('observacoes')
                                ->label('Observações'),
                        ]),
                    ]),

                // Ação de alteração de status
                Actions::make([
                    Action::make('changeStatus')
                        ->label('Mudar Status')
                        ->action('openChangeStatusModal')
                        ->button(),
                ])
                    ->fullWidth(),

                Section::make('Produtos')
                    ->schema([
                        RepeatableEntry::make('negociacaoProdutos')
                            ->schema([
                                // Linha resumida
                                Grid::make(5)->schema([
                                    TextEntry::make('produto.nome_composto')
                                        ->label('Produto')
                                        ->columnSpan(3),

                                    TextEntry::make('volume')
                                        ->label('Volume'),
                                    TextEntry::make('margem_percentual_rs')
                                        ->label('Margem (%)')
                                        ->suffix('%')
                                        ->visible(fn() => $record->moeda_id === 1),
                                    TextEntry::make('margem_percentual_us')
                                        ->label('Margem (%)')
                                        ->suffix('%')
                                        ->visible(fn() => $record->moeda_id === 2),
                                ]),

                                // Seção colapsável com todos os demais campos
                                Section::make('Ver mai  detalhes do produto')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Grid::make(6)->schema([
                                            // Índice
                                            TextEntry::make('indice_valorizacao')
                                                ->label('Índice Valorização'),

                                            // Preço unitário (snap)
                                            TextEntry::make('snap_produto_preco_rs')
                                                ->label('Preço Unit. (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('snap_produto_preco_us')
                                                ->label('Preço Unit. (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Custo unitário (snap)
                                            TextEntry::make('snap_produto_custo_rs')
                                                ->label('Custo Unit. (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('snap_produto_custo_us')
                                                ->label('Custo Unit. (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Totais negociados
                                            TextEntry::make('preco_total_produto_negociacao_rs')
                                                ->label('Total Preço Neg. (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('preco_total_produto_negociacao_us')
                                                ->label('Total Preço Neg. (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            TextEntry::make('custo_total_produto_negociacao_rs')
                                                ->label('Total Custo Neg. (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('custo_total_produto_negociacao_us')
                                                ->label('Total Custo Neg. (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Margem de faturamento
                                            TextEntry::make('margem_faturamento_rs')
                                                ->label('Margem Faturamento (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('margem_faturamento_us')
                                                ->label('Margem Faturamento (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Preço valorizado unitário
                                            TextEntry::make('preco_produto_valorizado_rs')
                                                ->label('Preço Valorizado (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('preco_produto_valorizado_us')
                                                ->label('Preço Valorizado (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Totais calculados

                                            TextEntry::make('total_preco_rs')
                                                ->label('Total (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('total_preco_us')
                                                ->label('Total (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            TextEntry::make('total_preco_valorizado_rs')
                                                ->label('Total Valorizado (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('total_preco_valorizado_us')
                                                ->label('Total Valorizado (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Preço líquido da saca
                                            TextEntry::make('preco_liquido_saca')
                                                ->label('Preço Líquido saca (R$)')
                                                ->money('BRL')
                                                ->visible(fn() => $record->moeda_id === 1),

                                            TextEntry::make('preco_liquido_saca')
                                                ->label('Preço Líquido saca (US$)')
                                                ->money('USD')
                                                ->visible(fn() => $record->moeda_id === 2),

                                            // Data de atualização
                                            TextEntry::make('data_atualizacao_snap_precos_produtos')
                                                ->label('Data Atualização')
                                                ->date(),
                                        ]),
                                    ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
