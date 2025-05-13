<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Models\Pagamento;
use Carbon\Carbon;

class PagamentosSectionForm
{
    public static function make(): Section
    {
        return Section::make('Pagamentos')
            ->schema([
                Select::make('pagamento_id')
                    ->label('Data de Pagamento')
                    ->options(Pagamento::all()->pluck('data_pagamento', 'id')->mapWithKeys(fn ($v, $k) => [$k => Carbon::parse($v)->format('d/m/Y')])->toArray())
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, $set) => $set('data_entrega_graos', Pagamento::find($state)?->data_entrega)),
                DatePicker::make('data_entrega_graos')
                    ->label('Data de Entrega dos Grãos')
                    ->required()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),
            ])
            ->columns(2);
    }
}
