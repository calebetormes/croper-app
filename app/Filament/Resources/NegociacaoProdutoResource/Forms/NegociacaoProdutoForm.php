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


class NegociacaoProdutoForm
{
    public static function make(): array
    {
        return [
            Repeater::make('negociacaoProdutos')
                ->relationship('negociacaoProdutos')
                ->reactive() // re-render repeater when moeda_id changes
                ->label('Produtos')
                ->collapsed()
                ->defaultItems(0)
                ->createItemButtonLabel('Adicionar Produto')
                ->reorderable()
                ->grid(1)
                ->reactive()
                ->itemLabel(
                    fn(array $state): ?string =>
                    Produto::find($state['produto_id'])?->nome_composto
                    ?? 'Novo Produto'
                )
                ->schema([
                    // Exibe a moeda selecionada (1 = BRL, 2 = USD)
                    Section::make('')
                        ->columns(2)
                        ->schema([
                            Select::make('produto_id')
                                ->label('Produto')
                                ->relationship('produto', 'nome')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->getOptionLabelFromRecordUsing(fn(Produto $r) => $r->nome_composto)
                                ->required()
                                ->afterStateUpdated(
                                    fn(Get $get, Set $set) =>
                                    NegociacaoProdutoLogic::produtoSelectAfterStateUpdated($get, $set)
                                ),

                            TextInput::make('volume')
                                ->label('VolumeB')
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
                        ]),

                    Auth::user()?->hasAnyRole(['vendedor', 'gerente_comercial'])
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
