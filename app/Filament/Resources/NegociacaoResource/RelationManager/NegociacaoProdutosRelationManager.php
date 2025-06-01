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

                    // 1.1) Busca dados do produto selecionado
                    $produto = Produto::find($get('produto_id'));

                    // 1.2) Preenche os campos de preço
                    $set('snap_produto_preco_rs', $produto?->preco_rs);
                    $set('snap_produto_preco_us', $produto?->preco_us);

                    // 1.4) Data de atualização dos preços (formato YYYY-MM-DD para DatePicker)
                    $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                    // ───────────────────────────────────────────────────
                    // 1.4) Agora calcula os preços virtuais usando o estado “snap” * o estado do “fator”
                    //      Observe que usamos o valor que está em $get('snap_produto_preco_rs') e 
                    //      $get('negociacao_produto_fator_valorizacao'), pois estes campos existem em 
                    //      NegociacaoProduto e já estão no form state.
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
                ->afterStateUpdated(function (Get $get, Set $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoRs = $get('snap_produto_preco_rs') ?? 0;
                        $set('negociacao_produto_preco_virtual_rs', $precoRs * $fator);
                    }
                }),

            TextInput::make('snap_produto_preco_us')
                ->label('Preço do Produto na Negociação (US$) ')
                ->numeric()
                ->prefix('US$')
                ->required()
                ->reactive()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoUs = $get('snap_produto_preco_us') ?? 0;
                        $set('negociacao_produto_preco_virtual_us', $precoUs * $fator);
                    }
                }),

            Actions::make([
                Action::make('resetar_precos_fixados')
                    ->label('Atualizar Preços do Produto')
                    ->color('primary')
                    ->action(function (Get $get, Set $set) {
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
                ->afterStateUpdated(function (Get $get, Set $set) {
                    if ($get('produto_id')) {
                        $fator = $get('negociacao_produto_fator_valorizacao') ?? 0;
                        $precoRs = $get('snap_produto_preco_rs') ?? 0;
                        $precoUs = $get('snap_produto_preco_us') ?? 0;

                        $set('negociacao_produto_preco_virtual_rs', $precoRs * $fator);
                        $set('negociacao_produto_preco_virtual_us', $precoUs * $fator);
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
                Tables\Columns\TextColumn::make('produto.nome_composto')
                    ->label('Produto')
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Produtos a Negociação'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
