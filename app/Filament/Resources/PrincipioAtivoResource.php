<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrincipioAtivoResource\Pages;
use App\Models\PrincipioAtivo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrincipioAtivoResource extends Resource
{
    protected static ?string $model = PrincipioAtivo::class;

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

    protected static ?string $navigationLabel = 'Princípios Ativos';

    protected static ?int $navigationSort = 3;

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
            'index' => Pages\ManagePrincipioAtivos::route('/'),
        ];
    }
}
