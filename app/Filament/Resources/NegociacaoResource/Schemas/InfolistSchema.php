<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class InfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informações Gerais')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('pedido_id')->label('Pedido ID'),
                            TextEntry::make('data_negocio')->label('Data Negócio')->date(),
                            TextEntry::make('cliente')->label('Cliente'),
                            TextEntry::make('cidade_cliente')->label('Cidade'),
                            TextEntry::make('gerente.name')->label('Gerente'),
                            TextEntry::make('vendedor.name')->label('Vendedor'),
                            TextEntry::make('valor_total_pedido_rs')->label('Total (R$)')->money('BRL'),
                            TextEntry::make('valor_total_pedido_us')->label('Total (US$)')->money('USD'),
                            TextEntry::make('area_hectares')->label('Área (ha)'),
                        ]),
                    ]),

                Section::make('Produtos da Negociação')
                    ->schema([
                        RepeatableEntry::make('negociacaoProdutos')
                            ->schema([
                                Grid::make(4)->schema([
                                    TextEntry::make('produto.nome')->label('Produto'),
                                    TextEntry::make('volume')->label('Volume'),
                                    TextEntry::make('snap_produto_preco_rs')->label('Preço Unitário (R$)')->money('BRL'),
                                    TextEntry::make('total_preco_rs')->label('Preço Total (R$)')->money('BRL'),
                                    TextEntry::make('snap_produto_preco_us')->label('Preço Unitário (US$)')->money('USD'),
                                    TextEntry::make('total_preco_us')->label('Preço Total (US$)')->money('USD'),
                                    TextEntry::make('indice_valorizacao')->label('Índice Valorização'),
                                ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }
}
