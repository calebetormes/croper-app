<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarcaComercialResource\Pages;
use App\Models\MarcaComercial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarcaComercialResource extends Resource
{
    protected static ?string $model = MarcaComercial::class;

    protected static ?string $navigationGroup = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-chevron-right';

    protected static ?string $navigationLabel = 'Marcas Comerciais';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
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
            'index' => Pages\ManageMarcaComercials::route('/'),
        ];
    }
}
