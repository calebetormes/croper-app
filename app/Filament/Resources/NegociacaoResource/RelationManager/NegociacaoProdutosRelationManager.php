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
                ->afterStateUpdated(function ($get, $set) {

                    // 1.1) Busca dados do produto selecionado
                    $produto = Produto::find($get('produto_id'));

                    // 1.2) Preenche os campos de preço
                    $set('snap_produto_preco_rs', $produto?->preco_rs);
                    $set('snap_produto_preco_us', $produto?->preco_us);

                    // 1.4) Data de atualização dos preços (formato YYYY-MM-DD para DatePicker)
                    $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                    // 1.5) Extrai valores em variáveis intermediárias
                    $snapRs = $get('snap_produto_preco_rs') ?? 0;
                    $snapUs = $get('snap_produto_preco_us') ?? 0;
                    $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;

                    // 1.6) Calcula preços virtuais
                    $virtualRs = $snapRs + ($snapRs * $fator);                              // mantém fórmula original
                    $virtualUs = $snapUs + ($snapUs * $fator);                  // nova fórmula: snap + (snap * fator)
        
                    // 1.7) Seta no estado
                    $set('negociacao_produto_preco_virtual_rs', $virtualRs);
                    $set('negociacao_produto_preco_virtual_us', $virtualUs);

                }),

            TextInput::make('volume')
                ->label('Volume')
                ->numeric()
                ->required()
                ->step(
                    fn(Get $get) =>
                    optional(Produto::find($get('produto_id')))
                        ->fator_multiplicador
                    ?? 1
                )
                ->rules(fn(Get $get) => [
                    'required',
                    'numeric',
                    'multiple_of:' .
                    (optional(Produto::find($get('produto_id')))
                        ->fator_multiplicador
                        ?? 1),
                ])
                ->helperText(
                    fn(Get $get) =>
                    'Somente múltiplos de ' .
                    (optional(Produto::find($get('produto_id')))
                        ->fator_multiplicador
                        ?? 1)
                ),


            TextInput::make('snap_produto_preco_rs')
                ->label('Preço do Produto na Negociação (R$)')
                ->numeric()
                ->prefix('R$')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($get, $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoRs = $get('snap_produto_preco_rs') ?? 0;
                        $set('negociacao_produto_preco_virtual_rs', $precoRs + $precoRs * $fator);
                    }
                }),

            TextInput::make('snap_produto_preco_us')
                ->label('Preço do Produto na Negociação (US$) ')
                ->numeric()
                ->prefix('US$')
                ->required()
                ->reactive()

                ->afterStateUpdated(function ($get, $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoUs = $get('snap_produto_preco_us') ?? 0;
                        $set('negociacao_produto_preco_virtual_us', $precoUs + $precoUs * $fator);
                    }
                }),


            Actions::make([
                Action::make('resetar_precos_fixados')
                    ->label('Atualizar Preços do Produto')
                    ->color('primary')
                    ->action(function ($get, $set) {
                        $produto = Produto::find($get('produto_id'));

                        // 1) Preenche os campos de preço com os valores atuais do Produto
                        $set('snap_produto_preco_rs', $produto?->preco_rs);
                        $set('snap_produto_preco_us', $produto?->preco_us);

                        // 2) Atualiza a data de fixação de preços
                        $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                        // 3) Calcula os preços virtuais (snap * fator)
                        $set(
                            'negociacao_produto_preco_virtual_rs',
                            ($get('snap_produto_preco_rs') ?? 0)
                            * ($get('negociacao_produto_fator_valorizacao') ?? 0)
                        );
                        $set(
                            'negociacao_produto_preco_virtual_us',
                            ($get('snap_produto_preco_us') ?? 0)
                            * ($get('negociacao_produto_fator_valorizacao') ?? 0)
                        );
                    }),
            ]),


            DatePicker::make('data_atualizacao_snap_precos_produtos')
                ->label('Data de Atualização dos Preços')
                ->required()
                ->disabled()
                ->dehydrated(),


            TextInput::make('negociacao_produto_fator_valorizacao')
                ->label('Fator de Valorização')
                ->default(fn() => $this->ownerRecord->snap_praca_cotacao_fator_valorizacao)
                ->numeric()
                ->required()
                ->dehydrated()
                ->reactive()

                //Calcula os preços virtuais quando o fator de valorização é atualizado
                ->afterStateUpdated(function ($get, $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoRs = $get('snap_produto_preco_rs') ?? 0;
                        $precoUs = $get('snap_produto_preco_us') ?? 0;

                        $set('negociacao_produto_preco_virtual_rs', $precoRs + $precoRs * $fator);
                        $set('negociacao_produto_preco_virtual_us', $precoUs + $precoUs * $fator);
                    }
                }),


            TextInput::make('negociacao_produto_preco_virtual_rs')
                ->label('Preço Virtual (R$)')
                ->prefix('R$')
                ->numeric()
                ->required(),


            TextInput::make('negociacao_produto_preco_virtual_us')
                ->label('Preço Virtual (US$)')
                ->prefix('US$')
                ->numeric()
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('produto.nome_composto')
                    ->label('Produto')
                    ->sortable(),

                TextColumn::make('volume')
                    ->sortable(),

                TextColumn::make('snap_produto_preco_rs')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Produtos a Negociação')
                    ->after(fn(Component $livewire) => $livewire->dispatch('negociacaoProdutoUpdated')),
            ])
            ->actions([
                EditAction::make()
                    ->after(fn(Component $livewire) => $livewire->dispatch('negociacaoProdutoUpdated')),

                DeleteAction::make()
                    ->after(fn(Component $livewire) => $livewire->dispatch('negociacaoProdutoUpdated')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn(Component $livewire) => $livewire->dispatch('negociacaoProdutoUpdated')),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->after(fn(Component $livewire) => $livewire->dispatch('negociacaoProdutoUpdated')),
            ]);
    }
}
