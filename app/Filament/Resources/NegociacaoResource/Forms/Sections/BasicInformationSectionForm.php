<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use App\Models\Moeda;
use App\Models\User;

class BasicInformationSectionForm
{
    public static function make(): Section
    {
        return Section::make('Informações Básicas')
            ->schema([
                DatePicker::make('data_versao')
                    ->label('Data Versão')
                    ->default(now())
                    ->disabled()
                    ->hidden()
                    ->dehydrated(),
                DatePicker::make('data_negocio')
                    ->label('Data da Negociação')
                    ->default(now())
                    ->required(),
                ToggleButtons::make('moeda_id')
                    ->label('Moeda')
                    ->options(Moeda::pluck('sigla', 'id')->toArray())
                    ->required()
                    ->inline(),
                Select::make('vendedor_id')
                    ->label('RTV')
                    ->options(fn () => auth()->user()?->role?->name === 'Vendedor'
                        ? [auth()->id() => auth()->user()->name]
                        : User::pluck('name', 'id')->toArray()
                    )
                    ->searchable()
                    ->required()
                    ->dehydrated(),
                Select::make('gerente_id')
                    ->label('Gerente')
                    ->options(fn () => auth()->user()?->role?->name === 'Gerente Comercial'
                        ? [auth()->id() => auth()->user()->name]
                        : User::whereRelation('role', 'name', 'Gerente Comercial')->pluck('name', 'id')->toArray()
                    )
                    ->searchable()
                    ->required()
                    ->dehydrated(),
            ])
            ->columns(4);
    }
}
