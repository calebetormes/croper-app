<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NegociacaoProdutoResource\Pages;
use App\Filament\Resources\NegociacaoProdutoResource\RelationManagers;
use App\Models\NegociacaoProduto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Navigation\NavigationItem;

class NegociacaoProdutoResource extends Resource
{
    protected static ?string $model = NegociacaoProduto::class;

    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Moedas';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl())
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->visible(fn() => in_array(auth()->user()?->role_id, [5])),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, [5]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('negociacao_id')
                    ->relationship('negociacao', 'id')
                    ->required(),

                Forms\Components\TextInput::make('produto_id')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('volume')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('potencial_produto')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('dose_hectare')
                    ->required()
                    ->numeric(),

                //SNAPS
                Forms\Components\TextInput::make('snap_produto_preco_rs')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('snap_produto_preco_us')
                    ->required()
                    ->numeric(),

                Forms\Components\Toggle::make('snap_precos_fixados')
                    ->required(),

                Forms\Components\DatePicker::make('data_atualizacao_snap_precos_produtos')
                    ->required(),

                //FORMULAS
                Forms\Components\TextInput::make('negociacao_produto_fator_valorizacao')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('negociacao_produto_preco_virtual_rs')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('negociacao_produto_preco_virtual_us')
                    ->required()
                    ->numeric(),


            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('negociacao.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('produto_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('potencial_produto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_hectare')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snap_produto_preco_real_rs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snap_produto_preco_real_us')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snap_produto_preco_virtual_rs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('snap_produto_preco_virtual_us')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('snap_precos_fixados')
                    ->boolean(),
                Tables\Columns\TextColumn::make('data_atualizacao_snap_precos_produtos')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNegociacaoProdutos::route('/'),
        ];
    }
}
