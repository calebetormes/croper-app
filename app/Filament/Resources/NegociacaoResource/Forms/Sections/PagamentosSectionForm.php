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
                    ->options(function (callable $get) {
                        $query = \App\Models\Pagamento::query()
                            ->where('ativo', true);

                        // mantém o pagamento atual mesmo se estiver inativo
                        if ($id = $get('pagamento_id')) {
                            $query->orWhere('id', $id);
                        }

                        return $query
                            ->orderByDesc('data_pagamento')
                            ->get()
                            ->mapWithKeys(fn($p) => [
                                $p->id => \Carbon\Carbon::parse($p->data_pagamento)->format('d/m/Y'),
                            ])
                            ->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, $set) =>
                        $set('data_entrega_graos', \App\Models\Pagamento::find($state)?->data_entrega)
                    ),

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
