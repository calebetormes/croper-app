<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component as LivewireComponent;
use Illuminate\Support\Arr;
use App\Models\Produto;

class QuantidadeMinimaSectionForm
{
    public static function make(): Section
    {
        return Section::make('')
            ->schema([

                Placeholder::make('status_defensivos_label')
                    ->label('Quantidade Mínima de Defensivos 3 (H, I, S)')
                    ->content(function (Get $get) {
                        $items = Arr::wrap($get('negociacaoProdutos'));
                        $count = collect($items)
                            ->filter(fn($item) => in_array(
                                Produto::find($item['produto_id'])?->classe?->nome,
                                ['H', 'I', 'S', 'F'],
                            ))
                            ->count();

                        return $count >= 3
                            ? "✅ Defensivos Selecionados: {$count}"
                            : "⚠️ Defensivos Selecionados: {$count}";
                    })
                    ->extraAttributes(function (Get $get) {
                        $items = Arr::wrap($get('negociacaoProdutos'));
                        $count = collect($items)
                            ->filter(fn($item) => in_array(
                                Produto::find($item['produto_id'])?->classe?->nome,
                                ['H', 'I', 'S'],
                            ))
                            ->count();

                        return [
                            'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium ' .
                                ($count >= 3
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-red-100 text-red-800'
                                ),
                        ];
                    })
                    ->reactive(),

                // 2) Verificação de ESPECIALIDADES (ESP, BIO, OL, POL)
                Placeholder::make('status_especialidades_label')
                    ->label('Quantidade Mínima de Especialidades 2 (ESP, BIO, OL, POL)')
                    ->content(function (Get $get) {
                        $items = Arr::wrap($get('negociacaoProdutos'));
                        $count = collect($items)
                            ->filter(fn($item) => in_array(
                                Produto::find($item['produto_id'])?->classe?->nome,
                                ['ESP', 'BIO', 'OL', 'POL'],
                            ))
                            ->count();

                        return $count >= 2
                            ? "✅ Especialidades Selecionadas: {$count}"
                            : "⚠️ Especialidades Selecionadas: {$count}";
                    })
                    ->extraAttributes(function (Get $get) {
                        $items = Arr::wrap($get('negociacaoProdutos'));
                        $count = collect($items)
                            ->filter(fn($item) => in_array(
                                Produto::find($item['produto_id'])?->classe?->nome,
                                ['ESP', 'BIO', 'OL', 'POL'],
                            ))
                            ->count();

                        return [
                            'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium ' .
                                ($count >= 2
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800'
                                ),
                        ];
                    })
                    ->reactive(),
            ])
            ->columns(2);
    }
}
