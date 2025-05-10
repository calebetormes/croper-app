<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnidadePesoResource\Pages;
use App\Models\UnidadePeso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnidadePesoResource extends Resource
{
    protected static ?string $model = UnidadePeso::class;

    protected static ?string $navigationGroup = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-chevron-right';

    protected static ?string $navigationLabel = 'Unidades de Peso';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sigla')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descricao')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sigla')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable(),
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
            'index' => Pages\ManageUnidadePesos::route('/'),
        ];
    }
}
