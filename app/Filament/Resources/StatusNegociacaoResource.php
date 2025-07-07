<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusNegociacaoResource\Pages;
use App\Filament\Resources\StatusNegociacaoResource\RelationManagers;
use App\Models\StatusNegociacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Navigation\NavigationItem;

class StatusNegociacaoResource extends Resource
{
    protected static ?string $model = StatusNegociacao::class;

    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Status das Negociações';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl())
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
                ->sort(static::getNavigationSort())
                ->visible(fn() => in_array(auth()->user()?->role_id, [6, 5])),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, [6, 5]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descricao')
                    ->columnSpanFull(),
                Forms\Components\ColorPicker::make('cor'),
                Forms\Components\TextInput::make('ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('icone')
                    ->maxLength(255),
                Forms\Components\Toggle::make('finaliza_negociacao')
                    ->required(),
                Forms\Components\Toggle::make('ativo')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ordem')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('icone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('finaliza_negociacao')
                    ->boolean(),
                Tables\Columns\IconColumn::make('ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManageStatusNegociacaos::route('/'),
        ];
    }
}
