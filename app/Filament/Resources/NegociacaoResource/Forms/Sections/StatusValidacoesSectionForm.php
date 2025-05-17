<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component as LivewireComponent;

class StatusValidacoesSectionForm
{
    public static function make(): Section
    {
        return Section::make('Status e Validações')
            ->schema([
                Select::make('nivel_validacao_id')
                    ->label('Que poderá validar')
                    ->options([1 => 'Vendedor', 2 => 'Gerente Comercial', 3 => 'Gerente Nacional', 4 => 'Admin'])
                    ->default(4)
                    ->searchable()
                    ->required()
                    ->disabled(fn (Get $get): bool => auth()->user()->role_id === 1 ||
                        auth()->user()->role_id < $get('nivel_validacao_id') ||
                        (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                    )
                    ->dehydrated(),

                ToggleButtons::make('status_validacao')
                    ->label('Status de Validação')
                    ->options([0 => 'Aguardando', 1 => 'Aprovado'])
                    ->colors([0 => 'warning', 1 => 'success'])
                    ->default(0)
                    ->inline()
                    ->dehydrated()
                    ->disabled(fn (Get $get): bool => auth()->user()->role_id === 1 ||
                        auth()->user()->role_id < $get('nivel_validacao_id') ||
                        (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                    ),

                // 1) cria um hidden que conta quantos Produtos da negociação têm princípio ativo H, I, ou S
                Hidden::make('status_defensivos')
                    ->afterStateHydrated(function (Get $get, Set $set, LivewireComponent $livewire) {
                        // tenta obter o registro; em Create isso pode ser null
                        $negociacao = $livewire->getRecord();

                        if (! $negociacao) {
                            // cria um 0 como fallback
                            $set('status_defensivos', 0);

                            return;
                        }

                        // conta quantos pivot NegociacaoProduto ligados a esta negociação têm
                        // princípio ativo em ESP, BIO, OL ou POL
                        $count = $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn ($q) => $q->whereIn('nome', ['H', 'I', 'S'])
                            )
                            ->count();

                        $set('status_defensivos', $count);
                    })
                    ->dehydrateStateUsing(function (Get $get, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (! $negociacao) {
                            return 0;
                        }

                        return $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn ($q) => $q->whereIn('nome', ['H', 'I', 'S'])
                            )
                            ->count();
                    }),

                Placeholder::make('status_defensivos_label')
                    ->label('Quantidade Mínima de Defensivos')
                    ->content(fn (Get $get) => $get('status_defensivos') >= 3
                        ? '✅ Atingido'
                        : '⚠️ Adicione mais defensivos à sua negociação'
                    )

                    ->extraAttributes(fn (Get $get) => [
                        'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium '.($get('status_defensivos') >= 3
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'),
                    ]),

                // 1) cria um hidden que conta quantos Produtos da negociação têm princípio ativo ESP, BIO, OL ou POL
                Hidden::make('status_especialidades')
                    ->afterStateHydrated(function (Get $get, Set $set, LivewireComponent $livewire) {
                        // tenta obter o registro; em Create isso pode ser null
                        $negociacao = $livewire->getRecord();

                        if (! $negociacao) {
                            // cria um 0 como fallback
                            $set('status_especialidades', 0);

                            return;
                        }

                        // conta quantos pivot NegociacaoProduto ligados a esta negociação têm
                        // princípio ativo em ESP, BIO, OL ou POL
                        $count = $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn ($q) => $q->whereIn('nome', ['ESP', 'BIO', 'OL', 'POL'])
                            )
                            ->count();

                        $set('status_especialidades', $count);
                    })
                    ->dehydrateStateUsing(function (Get $get, LivewireComponent $livewire) {
                        $negociacao = $livewire->getRecord();
                        if (! $negociacao) {
                            return 0;
                        }

                        return $negociacao
                            ->negociacaoProdutos()
                            ->whereHas('produto.classe', fn ($q) => $q->whereIn('nome', ['ESP', 'BIO', 'OL', 'POL'])
                            )
                            ->count();
                    }),

                Placeholder::make('status_especialidades_label')
                    ->label('Quantidade Mínima de Especialidades')
                    ->content(fn (Get $get) => $get('status_especialidades') >= 2
                        ? '✅ Atingido'
                        : '⚠️ Adicione mais especialidades à sua negociação'
                    )
                    ->extraAttributes(fn (Get $get) => [
                        'class' => $get('status_especialidades') >= 2
                            ? 'text-green-500 font-semibold'
                            : 'text-yellow-400 font-semibold',
                    ]),
            ])
            ->columns(4);
    }
}
