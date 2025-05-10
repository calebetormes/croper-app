<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PracaCotacaoResource\Pages;
use App\Filament\Resources\PracaCotacaoResource\RelationManagers;
use App\Models\PracaCotacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ToggleButtons;
use App\Models\Moeda;
use App\Models\Cultura;

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

                Forms\Components\DatePicker::make('data_vencimento')
                    ->required(),

                TextInput::make('praca_cotacao_preco')
                    ->label('Preço')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->prefix(function ($get) {
                        $moedaId = $get('moeda_id');
                        $moeda = Moeda::find($moedaId);
                        return $moeda?->sigla ?? '';
                    })
                    ->inputMode('decimal'),    // teclado numérico
                     
                ToggleButtons::make('cultura_id')
                    ->label('Cultura')
                    ->options([
                        1 => 'SOJA',
                        2 => 'MILHO',
                    ])
                    ->inline()
                    ->required(),

                ToggleButtons::make('moeda_id')
                    ->label('Moeda')
                    ->options(fn () => \App\Models\Moeda::all()->pluck('nome', 'id')->toArray())
                    ->inline()
                    ->reactive()
                    ->required()
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
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->date()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('preco')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cultura_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('moeda_id')
                    ->numeric()
                    ->sortable(),
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
