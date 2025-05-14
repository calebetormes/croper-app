<?php

namespace App\Filament\Resources\NegociacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class NegociacaoProdutosRelationManager extends RelationManager
{
    protected static string $relationship = 'negociacaoProdutos';

    protected static ?string $title = 'Produtos da Negociação';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('produto_id')
                ->label('Produto (ID)')
                ->options(\App\Models\Produto::pluck('id', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('volume')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('potencial_produto')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('dose_hectare')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('snap_produto_preco_real_rs')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('snap_produto_preco_real_us')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('snap_produto_preco_virtual_rs')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('snap_produto_preco_virtual_us')
                ->numeric()
                ->required(),

            Forms\Components\Toggle::make('snap_precos_fixados')
                ->required(),

            Forms\Components\DatePicker::make('data_atualizacao_snap_precos_produtos')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto_id')
                    ->label('Produto ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume')
                    ->sortable(),

                Tables\Columns\TextColumn::make('potencial_produto')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dose_hectare')
                    ->sortable(),

                Tables\Columns\TextColumn::make('snap_produto_preco_real_rs')
                    ->sortable(),

                Tables\Columns\TextColumn::make('snap_produto_preco_real_us')
                    ->sortable(),

                Tables\Columns\IconColumn::make('snap_precos_fixados')
                    ->boolean(),

                Tables\Columns\TextColumn::make('data_atualizacao_snap_precos_produtos')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
