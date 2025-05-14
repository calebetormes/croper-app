<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamiliaResource\Pages;
use App\Models\Familia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Navigation\NavigationItem;

class FamiliaResource extends Resource
{
    protected static ?string $model = Familia::class;

    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Familias';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 6;

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl())
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->visible(fn () => in_array(auth()->user()?->role_id, [4, 5])),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, [4, 5]);
    }

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
            'index' => Pages\ManageFamilias::route('/'),
        ];
    }
}
