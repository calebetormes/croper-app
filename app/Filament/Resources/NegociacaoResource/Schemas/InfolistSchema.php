<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Models\Negociacao;

class InfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        $record = $infolist->getRecord();

        return $infolist
            ->columns(2)
            ->schema([
                // Seção: Dados Gerais da Negociação
                InfolistSection::make('Dados da Negociação')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pedido_id')->label('Pedido ID'),
                        TextEntry::make('data_negocio')->label('Data da Negociação')
                            ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
                        TextEntry::make('cliente')->label('Cliente'),
                        TextEntry::make('cultura.nome')->label('Cultura'),
                        TextEntry::make('pracaCotacao.cidade')->label('Praça de Cotação'),
                        TextEntry::make('pracaCotacao.data_vencimento')->label('Vencimento')
                            ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
                        TextEntry::make('moeda.sigla')->label('Moeda'),
                    ]),

                // Seção: Valores e Métricas
                InfolistSection::make('Valores')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('snap_praca_cotacao_preco')
                            ->label(fn() => $record->moeda_id === 1 ? 'Preço Snapshot (R$)' : 'Preço Snapshot (US$)')
                            ->formatStateUsing(
                                fn($state) =>
                                $record->moeda_id === 1
                                ? 'R$ ' . number_format($state, 2, ',', '.')
                                : 'US$ ' . number_format($state, 2, ',', '.')
                            ),
                        TextEntry::make('area_hectares')
                            ->label('Área (ha)')
                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                        TextEntry::make('peso_total_kg')
                            ->label('Peso Total (kg)')
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                        TextEntry::make('valor_total_pedido_rs')
                            ->label('Total Pedido')
                            ->formatStateUsing(
                                fn($state) =>
                                $record->moeda_id === 1
                                ? 'R$ ' . number_format($state, 2, ',', '.')
                                : 'US$ ' . number_format($record->valor_total_pedido_us, 2, ',', '.')
                            ),
                        TextEntry::make('preco_liquido_saca')
                            ->label('Preço Líquido saca')
                            ->formatStateUsing(
                                fn() =>
                                $record->moeda_id === 1
                                ? 'R$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                                : 'US$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                            ),
                        TextEntry::make('margem_percentual_total_rs')
                            ->label('Margem (%)')
                            ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.') . '%'),
                    ]),

                // Seção: Status e Observações
                InfolistSection::make('Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('statusNegociacao.nome')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Em análise' => 'warning',
                                'Aprovado' => 'success',
                                'Não Aprovado' => 'danger',
                                'Concluído' => 'secondary',
                                default => 'secondary',
                            }),
                        TextEntry::make('nivelValidacao.nome')
                            ->label('Nível de Validação')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                '1' => 'primary',
                                '2' => 'warning',
                                '3' => 'danger',
                                default => 'secondary',
                            }),
                        TextEntry::make('observacoes')
                            ->label('Observações')
                            ->columnSpanFull(),
                    ]),

                // Seção: Produtos
                InfolistSection::make('Produtos')
                    ->schema([
                        RepeatableEntry::make('negociacaoProdutos')
                            ->schema([
                                // Linha resumida
                                Grid::make(5)->schema([
                                    TextEntry::make('produto.nome_composto')
                                        ->label('Produto')
                                        ->columnSpan(3),
                                    TextEntry::make('volume')
                                        ->label('Volume')
                                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                                    TextEntry::make('margem_percentual_rs')
                                        ->label('Margem (%)')
                                        ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.') . '%'),
                                ]),

                                // Detalhes expandidos
                                InfolistSection::make('Detalhes do Produto')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Grid::make(6)->schema([
                                            TextEntry::make('indice_valorizacao')
                                                ->label('Índice Valorização'),
                                            TextEntry::make('snap_produto_preco_rs')
                                                ->label('Preço Unit. (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('snap_produto_preco_us')
                                                ->label('Preço Unit. (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('snap_produto_custo_rs')
                                                ->label('Custo Unit. (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('snap_produto_custo_us')
                                                ->label('Custo Unit. (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('preco_total_produto_negociacao_rs')
                                                ->label('Total Preço Neg. (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('preco_total_produto_negociacao_us')
                                                ->label('Total Preço Neg. (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('custo_total_produto_negociacao_rs')
                                                ->label('Total Custo Neg. (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('custo_total_produto_negociacao_us')
                                                ->label('Total Custo Neg. (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('margem_faturamento_rs')
                                                ->label('Margem Faturamento (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('margem_faturamento_us')
                                                ->label('Margem Faturamento (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('preco_produto_valorizado_rs')
                                                ->label('Preço Valorizado (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('preco_produto_valorizado_us')
                                                ->label('Preço Valorizado (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('total_preco_rs')
                                                ->label('Total (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('total_preco_us')
                                                ->label('Total (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('total_preco_valorizado_rs')
                                                ->label('Total Valorizado (R$)')
                                                ->formatStateUsing(fn($state) => 'R$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('total_preco_valorizado_us')
                                                ->label('Total Valorizado (US$)')
                                                ->formatStateUsing(fn($state) => 'US$ ' . number_format($state, 2, ',', '.')),
                                            TextEntry::make('preco_liquido_saca')
                                                ->label('Preço Líquido saca')
                                                ->formatStateUsing(
                                                    fn() =>
                                                    $record->moeda_id === 1
                                                    ? 'R$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                                                    : 'US$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                                                ),
                                            TextEntry::make('data_atualizacao_snap_precos_produtos')
                                                ->label('Data Atualização')
                                                ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
                                        ]),
                                    ]),
                            ])
                            ->columns(1),
                    ]),

                // Ações
                Actions::make([
                    Action::make('print')
                        ->label('Imprimir')
                        ->icon('heroicon-o-printer')
                        ->url('javascript:window.print()')
                        ->button(),
                    Action::make('changeStatus')
                        ->label('Mudar Status')
                        ->icon('heroicon-o-adjustments-vertical')
                        ->form([
                            StatusGeralSectionForm::make(),
                        ])
                        ->modalHeading('Alterar Status da Negociação')
                        ->modalWidth('xl')
                        ->requiresConfirmation()
                        ->action(function (array $data, Negociacao $record): void {
                            $record->fill($data)->save();
                        })
                        ->button(),
                ]),
            ]);
    }
}
