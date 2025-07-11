<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;

class ClientInformationSectionForm
{
    public static function make(): Section
    {
        return Section::make('Informações do Cliente')
            ->schema([

                TextInput::make('cliente')
                    ->label('Razão Social')
                    ->maxLength(255)
                    ->required(),

                TextInput::make('endereco_cliente')
                    ->label('Endereço')
                    ->maxLength(255),

                Select::make('cidade_cliente')
                    ->label('Município')
                    ->options(collect(config('cidades'))->mapWithKeys(fn($cidade) => [$cidade => $cidade])->toArray())
                    ->searchable(),

                TextInput::make('area_hectares')
                    ->label('Área (ha)')
                    ->numeric()
                    ->placeholder('Ex: 12.5')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, callable $set, callable $get) =>
                        NegociacaoProdutoLogic::repeaterAfterStateUpdated($get, $set)
                    ),
            ])
            ->columns(3);
    }
}
