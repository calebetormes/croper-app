<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PracaCotacaoResource\Pages;
use App\Filament\Resources\PracaCotacaoResource\RelationManagers;
use App\Models\PracaCotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PracaCotacaoResource extends Resource
{
    protected static ?string $model = PracaCotacao::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cidade')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('preco')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('vencimento')
                    ->required(),
                Forms\Components\TextInput::make('preco_rs')
                    ->numeric(),
                Forms\Components\TextInput::make('preco_us')
                    ->numeric(),
                Forms\Components\TextInput::make('cultura_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preco')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vencimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preco_rs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preco_us')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cultura_id')
                    ->numeric()
                    ->sortable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPracaCotacaos::route('/'),
            'create' => Pages\CreatePracaCotacao::route('/create'),
            'edit' => Pages\EditPracaCotacao::route('/{record}/edit'),
        ];
    }
}
