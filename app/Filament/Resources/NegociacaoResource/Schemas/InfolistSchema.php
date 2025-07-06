<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;

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

                // Seção de Produtos
                Section::make('Produtos')
                    ->schema([
                        RepeatableEntry::make('negociacaoProdutos')
                            ->schema([
                                Grid::make(5)->schema([
                                    TextEntry::make('produto.nome')
                                        ->label('Produto'),
                                    TextEntry::make('volume')
                                        ->label('Volume'),

                                    TextEntry::make('snap_produto_preco_rs')
                                        ->label('Preço Unit. (R$)')
                                        ->money('BRL')
                                        ->visible(fn() => $record->moeda_id === 1),
                                    TextEntry::make('snap_produto_preco_us')
                                        ->label('Preço Unit. (US$)')
                                        ->money('USD')
                                        ->visible(fn() => $record->moeda_id === 2),

                                    TextEntry::make('total_preco_rs')
                                        ->label('Total (R$)')
                                        ->money('BRL')
                                        ->visible(fn() => $record->moeda_id === 1),
                                    TextEntry::make('total_preco_us')
                                        ->label('Total (US$)')
                                        ->money('USD')
                                        ->visible(fn() => $record->moeda_id === 2),

                                    TextEntry::make('indice_valorizacao')
                                        ->label('Índice Valorização'),
                                ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
