<?php

namespace App\Filament\Resources\NegociacaoProdutoResource\Forms;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\Produto;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\Sections\DetalhesProdutoHidden;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\Sections\DetalhesProdutoVisible;
use Filament\Support\RawJs;


class NegociacaoProdutoForm
{
    public static function make(): array
    {
        return [
            // Repeater para gerenciar lista de produtos na negociação
            Repeater::make('negociacaoProdutos')

            // Define relacionamento com a tabela negociacaoProdutos
                ->relationship('negociacaoProdutos')
                ->reactive() // re-render repeater when moeda_id changes
                ->label('Produtos')
                ->collapsed()
                ->defaultItems(0)
                ->createItemButtonLabel('Adicionar Produto')
                ->reorderable()
                ->grid(1)
                ->itemLabel(
                    fn(array $state): ?string =>
                    Produto::find($state['produto_id'])?->nome_composto
                    ?? 'Novo Produto'
                )
                ->schema([
                    Section::make('')
                        ->columns(2)
                        ->schema([
                            
                            // Campo de seleção do produto
                            Select::make('produto_id')
                                ->label('Produto')
                                //->searchable()
                                ->preload()
                                ->live()
                                ->required()
                                ->relationship(
                                    name: 'produto',
                                    titleAttribute: 'apresentacao',
                                    modifyQueryUsing: function ($query, ?\App\Models\NegociacaoProduto $record) {
                                        $produtoId = $record?->produto_id; // registro atual, se existir
                            
                                        $query->where(function ($q) use ($produtoId) {
                                            $q->where('ativo', true);

                                            if ($produtoId) {
                                                $q->orWhere('id', $produtoId);
                                            }
                                        });
                                    }
                                )

                                ->getOptionLabelFromRecordUsing(fn(Produto $r): mixed => $r->nome_composto)

                                /*->getOptionLabelUsing(
                                    fn($id) => Produto::with(['classe', 'principioAtivo', 'marcaComercial'])
                                        ->find($id)?->nome_composto
                                )*/


                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::produtoSelectAfterStateUpdated($get, $set)
                                ),

                                // Campo para informar volume do produto
                            TextInput::make('volume')
                                ->label('Volume')
                                ->numeric()
                                ->live(onBlur: true)        // só dispara ao sair do campo
                                ->required()
                                ->dehydrated()              // opcional (true por padrão)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    // Se o usuário apagou tudo, considera 0
                                    if ($state === '' || !is_numeric($state)) {
                                        $state = 0;
                                    }

                                    // Como o gatilho é onBlur, não há “briga” em setar o próprio campo
                                    // (garante tipo numérico antes de sua lógica)
                                    $set('volume', (float) $state);

                                    // Sua lógica de totais
                                    NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set);
                                }),
                            
                            //Preço original do produto em USD
                            // Preço do produto em BRL
                            TextInput::make('snap_produto_preco_rs')
                                ->label('Preço do Produto')
                                ->prefix('BRL')
                                ->numeric()
                                ->live(onBlur: true) 
                                ->dehydrated()
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 1)
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set)
                                ),
                            
                            //Preço original do produto em USD
                            // Preço do produto em USD
                            TextInput::make('snap_produto_preco_us')
                                ->label('Preço do Produto')
                                ->prefix('USS')
                                ->numeric()
                                ->live(onBlur: true) 
                                ->dehydrated()
                                ->visible(fn(Get $get) => $get('../../moeda_id') === 2)
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set)
                                ),

                            



                            TextInput::make('preco_total_produto_negociacao_rs')
                                    ->label('Valor Total na Negociação')
                                    ->prefix('BRL')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->visible(fn(Get $get) => $get('../../moeda_id') === 1),

                            TextInput::make('preco_total_produto_negociacao_us')
                                    ->label('Valor Total na Negociação')
                                    ->prefix('USS')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->visible(fn(Get $get) => $get('../../moeda_id') === 2),


                            // Data da última atualização dos preços
                            DatePicker::make('data_atualizacao_snap_precos_produtos')
                                    ->label('Data Atualização dos Preços do Produto')
                                    ->default(fn(): \DateTime => now())
                                    ->disabled()
                                    ->dehydrated(),
                          ]),

                        Auth::user()?->hasAnyRole(['vendedor', 'Gerente Comercial'])
                        ? DetalhesProdutoHidden::section()
                        : DetalhesProdutoVisible::section(),

                    ])
                
                ->afterStateUpdated(
                    fn(Get $get, Set $set) =>
                    NegociacaoProdutoLogic::repeaterAfterStateUpdated($get, $set)
                ),
        ];
    }
}
