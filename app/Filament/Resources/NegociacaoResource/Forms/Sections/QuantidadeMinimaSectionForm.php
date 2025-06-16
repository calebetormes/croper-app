<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component as LivewireComponent;

class QuantidadeMinimaSectionForm
{
    public static function make(): Section
    {
        return Section::make('')
            ->schema([
                Hidden::make('status_defensivos')
                    ->afterStateHydrated(function (Get $get, Set $set, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (!$negociacao) {
                            $set('status_defensivos', 0);
                            return;
                        }
                        $count = $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn($q) => $q->whereIn('nome', ['H', 'I', 'S']))
                            ->count();
                        $set('status_defensivos', $count);
                    })
                    ->dehydrateStateUsing(function (Get $get, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (!$negociacao) {
                            return 0;
                        }
                        return $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn($q) => $q->whereIn('nome', ['H', 'I', 'S']))
                            ->count();
                    }),

                Placeholder::make('status_defensivos_label')
                    ->label('Quantidade Mínima de Defensivos')
                    ->content(
                        fn(Get $get) => $get('status_defensivos') >= 3
                        ? '✅ Atingido'
                        : '⚠️ Adicione mais defensivos à sua negociação'
                    )
                    ->extraAttributes(fn(Get $get) => [
                        'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium ' . (
                            $get('status_defensivos') >= 3
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800'
                        ),
                    ]),

                Hidden::make('status_especialidades')
                    ->afterStateHydrated(function (Get $get, Set $set, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (!$negociacao) {
                            $set('status_especialidades', 0);
                            return;
                        }
                        $count = $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn($q) => $q->whereIn('nome', ['ESP', 'BIO', 'OL', 'POL']))
                            ->count();
                        $set('status_especialidades', $count);
                    })
                    ->dehydrateStateUsing(function (Get $get, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (!$negociacao) {
                            return 0;
                        }
                        return $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn($q) => $q->whereIn('nome', ['ESP', 'BIO', 'OL', 'POL']))
                            ->count();
                    }),

                Placeholder::make('status_especialidades_label')
                    ->label('Quantidade Mínima de Especialidades')
                    ->content(
                        fn(Get $get) => $get('status_especialidades') >= 2
                        ? '✅ Atingido'
                        : '⚠️ Adicione mais especialidades à sua negociação'
                    )
                    ->extraAttributes(fn(Get $get) => [
                        'class' => $get('status_especialidades') >= 2
                            ? 'text-green-500 font-semibold'
                            : 'text-yellow-400 font-semibold',
                    ]),
            ])
            ->columns(2);
    }
}
