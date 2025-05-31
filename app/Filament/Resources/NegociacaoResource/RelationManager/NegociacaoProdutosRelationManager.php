<?php

namespace App\Filament\Resources\NegociacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Produto;
use App\Models\PracaCotacao;


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

                    // 1.3) Marca o toggle “snap_precos_fixados”
                    $set('snap_precos_fixados', true);

                    // 1.4) Data de atualização dos preços (formato YYYY-MM-DD para DatePicker)
                    $set('data_atualizacao_snap_precos_produtos', now()->toDateString());

                }),

            Forms\Components\TextInput::make('volume')
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


            Forms\Components\TextInput::make('snap_produto_preco_rs')
                ->label('Preço (R$) do Produto na Negociação')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('snap_produto_preco_us')
                ->label('Preço (US$) do Produto na Negociação')
                ->numeric()
                ->required(),

            // 5) TOGGLE “Preços Fixados” (marcado via afterStateUpdated)
            Forms\Components\Toggle::make('snap_precos_fixados')
                ->label('Preços Fixados')
                ->required(),

            Forms\Components\DatePicker::make('data_atualizacao_snap_precos_produtos')
                ->label('Data de Atualização dos Preços')
                ->required(),


            // ────────────────────────────────────────────────────────────────
            // 7) Fator de Valorização – será preenchido quando o produto for selecionado
            // ────────────────────────────────────────────────────────────────
            Forms\Components\TextInput::make('snap_praca_cotacao_fator_valorizacao')
                ->label('Fator de Valorização')
                ->numeric()
                ->required()
                ->dehydrated()
                ->reactive(),

            Forms\Components\TextInput::make('negociacao_produto_preco_virtual_rs')->numeric()->required(),
            Forms\Components\TextInput::make('negociacao_produto_preco_virtual_us')->numeric()->required(),
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
