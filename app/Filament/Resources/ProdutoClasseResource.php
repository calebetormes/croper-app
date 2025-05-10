<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoClasseResource\Pages;
use App\Models\ProdutoClasse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProdutoClasseResource extends Resource
{
    protected static ?string $model = ProdutoClasse::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Painel Administrativo';
    }

    public static function getNavigationCluster(): ?string
    {
        return 'PRODUTOS';
    }

    // protected static ?string $navigationGroup = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-chevron-right';

    protected static ?string $navigationLabel = 'Classes de Produto';

    protected static ?int $navigationSort = 2;

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
            'index' => Pages\ManageProdutoClasses::route('/'),
        ];
    }
}
