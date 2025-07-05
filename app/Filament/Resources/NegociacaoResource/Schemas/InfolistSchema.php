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
                Section::make('Dados Básicos')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('pedido_id')->label('Pedido ID'),
                            TextEntry::make('data_versao')->label('Data Versão')->date(),
                            TextEntry::make('data_negocio')->label('Data Negociação')->date(),
                            TextEntry::make('data_entrega_graos')->label('Data Entrega Grãos')->date(),
                        ]),
                    ]),

                Section::make('Cliente e Localização')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('cliente')->label('Cliente'),
                            TextEntry::make('endereco_cliente')->label('Endereço'),
                            TextEntry::make('cidade_cliente')->label('Cidade'),
                            TextEntry::make('cultura.nome')->label('Cultura'),
                            TextEntry::make('pracaCotacao.nome')->label('Praça de Cotação'),
                            TextEntry::make('pagamento.nome')->label('Pagamento'),
                        ]),
                    ]),

                Section::make('Moeda e Câmbio')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('moeda.nome')->label('Moeda'),
                            TextEntry::make('cotacao_moeda_usd_brl')->label('Cotação USD/BRL'),
                            // Exibe apenas em R$
                            TextEntry::make('snap_praca_cotacao_preco')
                                ->label('Preço Snapshot (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('snap_praca_cotacao_preco')
                                ->label('Preço Snapshot (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),
                            TextEntry::make('data_atualizacao_snap_preco_praca_cotacao')
                                ->label('Atualização Snapshot')->dateTime(),
                        ]),
                    ]),

                Section::make('Dimensões e Peso')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('area_hectares')->label('Área (ha)'),
                            TextEntry::make('peso_total_kg')->label('Peso Total (kg)'),
                        ]),
                    ]),

                Section::make('Valores Totais')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('valor_total_pedido_rs')
                                ->label('Total Pedido (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('valor_total_pedido_us')
                                ->label('Total Pedido (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),
                            TextEntry::make('valor_total_pedido_rs_valorizado')
                                ->label('Total Valorizado (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('valor_total_pedido_us_valorizado')
                                ->label('Total Valorizado (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),
                        ]),
                    ]),

                Section::make('Investimentos')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('investimento_total_sacas')->label('Inv. Total (sacas)'),
                            TextEntry::make('investimento_sacas_hectare')->label('Inv. saca/ha'),
                            TextEntry::make('indice_valorizacao_saca')->label('Índice Valorização'),
                            TextEntry::make('preco_liquido_saca')
                                ->label('Preço Líquido saca (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                            TextEntry::make('preco_liquido_saca')
                                ->label('Preço Líquido saca (US$)')
                                ->money('USD')
                                ->visible(fn() => $record->moeda_id === 2),
                            TextEntry::make('bonus_cliente_pacote')
                                ->label('Bônus Pacote (R$)')
                                ->money('BRL')
                                ->visible(fn() => $record->moeda_id === 1),
                        ]),
                    ]),

                Section::make('Status e Observações')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nivelValidacao.nome')->label('Nível Validação'),
                            TextEntry::make('statusNegociacao.nome')->label('Status Negociação'),
                            TextEntry::make('status_defensivos')->label('Defensivos'),
                            TextEntry::make('status_especialidades')->label('Especialidades'),
                            TextEntry::make('observacoes')->label('Observações'),
                        ]),
                    ]),

                Actions::make([
                    Action::make('changeStatus')
                        ->label('Mudar Status')
                        ->action('openChangeStatusModal')
                        ->button(),
                ])
                    ->fullWidth(),

                Section::make('Produtos da Negociação')
                    ->schema([
                        RepeatableEntry::make('negociacaoProdutos')
                            ->schema([
                                Grid::make(4)->schema([
                                    TextEntry::make('produto.nome')->label('Produto'),
                                    TextEntry::make('volume')->label('Volume'),
                                    TextEntry::make('snap_produto_preco_rs')
                                        ->label('Preço Unit. (R$)')
                                        ->money('BRL')
                                        ->visible(fn() => $record->moeda_id === 1),
                                    TextEntry::make('snap_produto_preco_rs')
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
                                    TextEntry::make('indice_valorizacao')->label('Índice Valorização'),
                                ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
