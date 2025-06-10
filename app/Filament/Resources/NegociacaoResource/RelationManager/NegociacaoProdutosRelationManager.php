<?php

namespace App\Filament\Resources\NegociacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Produto;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Tables\Actions\CreateAction;
use Livewire\Component;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class NegociacaoProdutosRelationManager extends RelationManager
{
    protected static string $relationship = 'negociacaoProdutos';
    protected static ?string $title = 'Produtos da Negociação';

    public function form(Forms\Form $form): Forms\Form
    {
        $opt = Produto::all()
            ->mapWithKeys(fn($p) => [$p->id => $p->nome_composto])
            ->toArray();

        return $form->schema([
            Forms\Components\Select::make('produto_id')
                ->label('Produto')
                ->options($opt)
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    $produto = Produto::find($get('produto_id'));
                    $set('snap_produto_preco_rs', $produto?->preco_rs);
                    $set('snap_produto_preco_us', $produto?->preco_us);
                    $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                    $snapRs = $get('snap_produto_preco_rs') ?? 0;
                    $snapUs = $get('snap_produto_preco_us') ?? 0;
                    $fator = $get('indice_valorizacao') ?? 0;

                    $set('preco_produto_valorizado_rs', $snapRs + ($snapRs * $fator));
                    $set('preco_produto_valorizado_us', $snapUs + ($snapUs * $fator));
                }),

            TextInput::make('volume')
                ->label('Volume')
                ->numeric()
                ->required()
                ->step(fn(Get $get) => optional(Produto::find($get('produto_id')))->fator_multiplicador ?? 1)
                ->rules(fn(Get $get) => [
                    'required',
                    'numeric',
                    'multiple_of:' . (optional(Produto::find($get('produto_id')))->fator_multiplicador ?? 1),
                ])
                ->helperText(fn(Get $get) => 'Somente múltiplos de ' . (optional(Produto::find($get('produto_id')))->fator_multiplicador ?? 1)),

            TextInput::make('snap_produto_preco_rs')
                ->label('Preço do Produto (R$)')
                ->numeric()
                ->prefix('R$')
                ->required()
                ->reactive()
                ->afterStateUpdated(
                    fn(Get $get, Set $set) =>
                    $set('preco_produto_valorizado_rs', ($get('snap_produto_preco_rs') ?? 0) + (($get('snap_produto_preco_rs') ?? 0) * ($get('indice_valorizacao') ?? 0)))
                ),

            TextInput::make('snap_produto_preco_us')
                ->label('Preço do Produto (US$)')
                ->numeric()
                ->prefix('US$')
                ->required()
                ->reactive()
                ->afterStateUpdated(
                    fn(Get $get, Set $set) =>
                    $set('preco_produto_valorizado_us', ($get('snap_produto_preco_us') ?? 0) + (($get('snap_produto_preco_us') ?? 0) * ($get('indice_valorizacao') ?? 0)))
                ),

            TextInput::make('preco_produto_valorizado_rs')
                ->label('Preço Valorizado (R$)')
                ->numeric()
                ->prefix('R$')
                ->required(),

            TextInput::make('preco_produto_valorizado_us')
                ->label('Preço Valorizado (US$)')
                ->numeric()
                ->prefix('US$')
                ->required(),

            DatePicker::make('data_atualizacao_snap_precos_produtos')
                ->label('Data Atualização Preços')
                ->required()
                ->disabled()
                ->dehydrated(),

            TextInput::make('indice_valorizacao')
                ->label('Índice de Valorização')
                ->default(0)
                ->numeric()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    $fator = $get('indice_valorizacao') ?? 0;
                    $snapRs = $get('snap_produto_preco_rs') ?? 0;
                    $snapUs = $get('snap_produto_preco_us') ?? 0;

                    $set('preco_produto_valorizado_rs', $snapRs + ($snapRs * $fator));
                    $set('preco_produto_valorizado_us', $snapUs + ($snapUs * $fator));
                }),

            Actions::make([
                Action::make('resetar_precos')
                    ->label('Atualizar Preços do Produto')
                    ->color('primary')
                    ->action(function (Get $get, Set $set) {
                        $produto = Produto::find($get('produto_id'));
                        $set('snap_produto_preco_rs', $produto?->preco_rs);
                        $set('snap_produto_preco_us', $produto?->preco_us);
                        $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                        $fator = $get('indice_valorizacao') ?? 0;
                        $set('preco_produto_valorizado_rs', ($get('snap_produto_preco_rs') ?? 0) + (($get('snap_produto_preco_rs') ?? 0) * $fator));
                        $set('preco_produto_valorizado_us', ($get('snap_produto_preco_us') ?? 0) + (($get('snap_produto_preco_us') ?? 0) * $fator));
                    }),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produto.nome_composto')->label('Produto')->sortable(),
                TextColumn::make('volume')->sortable(),
                TextColumn::make('snap_produto_preco_rs')->label('Snap R$')->sortable(),
                TextColumn::make('snap_produto_preco_us')->label('Snap US$')->sortable(),
                TextColumn::make('preco_produto_valorizado_rs')->label('Valorizado R$')->sortable(),
                TextColumn::make('preco_produto_valorizado_us')->label('Valorizado US$')->sortable(),
                TextColumn::make('indice_valorizacao')->label('Índice')->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Produtos a Negociação')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()

                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
            ]);
    }
}
