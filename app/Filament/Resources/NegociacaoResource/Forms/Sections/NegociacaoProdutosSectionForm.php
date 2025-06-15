<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Produto;
use Closure;

class NegociacaoProdutosSectionForm
{
    public static function make(): Section
    {
        return Section::make('Produtos')
            ->schema([
                Repeater::make('negociacaoProdutos')
                    ->relationship('negociacaoProdutos')
                    ->label('Produtos')
                    ->columns(4)
                    ->collapsible()
                    ->createItemButtonLabel('Adicionar Produto')
                    ->schema([
                        Select::make('produto_id')
                            ->label('Produto')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->relationship('produto', 'nome') // usa a coluna real para evitar erro
                            ->getOptionLabelFromRecordUsing(
                                fn(Produto $record): string => $record->nome_composto
                            )
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $produto = Produto::find($get('produto_id'));
                                if ($produto) {
                                    // snapshot do produto
                                    $set('snap_produto_preco_rs', $produto->preco_rs);
                                    $set('snap_produto_preco_us', $produto->preco_us);
                                    // data atualização só na criação
                                    if (!$get('data_atualizacao_snap_precos_produtos')) {
                                        $set('data_atualizacao_snap_precos_produtos', now());
                                    }
                                }
                            }),

                        TextInput::make('volume')
                            ->label('Volume')
                            ->numeric()
                            ->required(),

                        TextInput::make('indice_valorizacao')
                            ->label('Índice de Valorização')
                            ->numeric()
                            ->placeholder('0.10 para 10%')
                            ->live()
                            ->default(0)
                            ->required()

                            ->afterStateUpdated(function (Get $get, Set $set) {
                                // Raw values (podem vir como string, com vírgula)
                                $snapRsRaw = $get('snap_produto_preco_rs') ?? '0';
                                $snapUsRaw = $get('snap_produto_preco_us') ?? '0';
                                $rawIndice = $get('indice_valorizacao') ?? '0';

                                // Normaliza: vírgula → ponto e cast para float
                                $snapRs = floatval(str_replace(',', '.', $snapRsRaw));
                                $snapUs = floatval(str_replace(',', '.', $snapUsRaw));
                                $indice = floatval(str_replace(',', '.', $rawIndice));

                                // Cálculo seguro
                                $set('preco_produto_valorizado_rs', $snapRs * (1 + $indice));
                                $set('preco_produto_valorizado_us', $snapUs * (1 + $indice));
                            }),

                        DatePicker::make('data_atualizacao_snap_precos_produtos')
                            ->label('Data Atualização')
                            ->default(fn(): \DateTime => now())
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('snap_produto_preco_rs')
                            ->label('Preço RS')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('snap_produto_preco_us')
                            ->label('Preço US')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('preco_produto_valorizado_rs')
                            ->label('Valorizado RS')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('preco_produto_valorizado_us')
                            ->label('Valorizado US')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),


                    ])
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $items = $get('negociacaoProdutos') ?? [];

                        // 1) Total em R$
                        $totalRs = collect($items)
                            ->sum(
                                fn($item) =>
                                ($item['snap_produto_preco_rs'] ?? 0)
                                * ($item['volume'] ?? 0)
                            );
                        $set('valor_total_pedido_rs', $totalRs);

                        // 2) Total em US$
                        $totalUs = collect($items)
                            ->sum(
                                fn($item) =>
                                ($item['snap_produto_preco_us'] ?? 0)
                                * ($item['volume'] ?? 0)
                            );
                        $set('valor_total_pedido_us', $totalUs);

                        $averageIndice = collect($items)
                            ->map(function ($item) {
                                $raw = $item['indice_valorizacao'] ?? '0';
                                $normalized = str_replace(',', '.', $raw);
                                return is_numeric($normalized) ? (float) $normalized : 0.0;
                            })
                            ->filter(fn($v) => $v > 0 || $v === 0) // mantém só valores numéricos
                            ->avg();
                        $set('indice_valorizacao_saca', $averageIndice);

                        // 4) Valor Total R$ Valorizado
                        $totalValorizadoRs = $totalRs * (1 + $averageIndice);
                        $set('valor_total_pedido_rs_valorizado', $totalValorizadoRs);

                        // 5) Valor Total U$ Valorizado
                        $totalValorizadoUs = $totalUs * (1 + $averageIndice);
                        $set('valor_total_pedido_us_valorizado', $totalValorizadoUs);
                    }),
            ]);
    }
}
