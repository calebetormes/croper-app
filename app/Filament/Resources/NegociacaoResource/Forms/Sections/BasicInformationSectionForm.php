<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Moeda;
use App\Models\User;
use App\Filament\Resources\NegociacaoProdutoResource\Forms\NegociacaoProdutoLogic;



class BasicInformationSectionForm
{
    public static function make(): Section
    {
        return Section::make('Informações Básicas')
            ->schema([

                TextInput::make('pedido_id')
                    ->label('Pedido ID')
                    ->disabled()
                    ->hiddenOn('create'),

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
                    //->default(fn(): int => Moeda::where('sigla', 'BRL')->value('id'))
                    ->required()
                    ->inline()
                    ->reactive()
                    ->live()
                    //->disableOnEdit()
                    ->disabledOn('edit')

                    ->afterStateUpdated(function ($get, $set) {

                        // Força o repeater a re­executar toda a lógica de pricing
                        NegociacaoProdutoLogic::produtoSelectAfterStateUpdated($get, $set);
                        NegociacaoProdutoLogic::repeaterAfterStateUpdated($get, $set);
                        NegociacaoProdutoLogic::volumeAfterStateUpdated($get, $set);
                        NegociacaoProdutoLogic::indiceValorizacaoAfterStateUpdated($get, $set);
                        NegociacaoProdutoLogic::repeaterAfterStateUpdated($get, $set);

                        // 1) Pega os totais atuais
                        $totalRs = $get('valor_total_pedido_rs') ?? 0;
                        $totalUs = $get('valor_total_pedido_us') ?? 0;
                        $rawPrecoSaca = $get('preco_liquido_saca') ?? '0';

                        // 2) Normaliza o preço da saca
                        $precoSaca = floatval(str_replace(',', '.', $rawPrecoSaca)) ?: 1;

                        // 3) Descobre a sigla e escolhe a base
                        $sigla = optional(Moeda::find($get('moeda_id')))->sigla;
                        $base = strtoupper($sigla) === 'USD' ? $totalUs : $totalRs;

                        // 4) Recalcula e seta
                        $invest = $base / $precoSaca;
                        $set('investimento_total_sacas', round($invest, 2));
                    }),

                Select::make('vendedor_id')
                    ->label('RTV')
                    ->options(function () {
                        $user = auth()->user();
                        $role = optional($user->role)->name;

                        if ($role === 'Vendedor') {
                            return [$user->id => $user->name];
                        }

                        if ($role === 'Gerente Comercial') {
                            return \App\Models\GerenteVendedor::where('gerente_id', $user->id)
                                ->with('vendedor')
                                ->get()
                                ->pluck('vendedor.name', 'vendedor.id')
                                ->toArray();
                        }

                        return \App\Models\User::pluck('name', 'id')->toArray();
                    })
                    ->default(function () {
                        $user = auth()->user();
                        return optional($user->role)->name === 'Vendedor' ? $user->id : null;
                    })
                    ->disabled(fn() => optional(auth()->user()->role)->name === 'Vendedor')
                    ->searchable()
                    ->required()
                    ->dehydrated(),

                Select::make('gerente_id')
                    ->label('GRV')
                    ->options(function () {
                        $user = auth()->user();

                        if ($user?->role?->name === 'Vendedor') {
                            // Busca o gerente vinculado a este vendedor
                            $gerente = \App\Models\GerenteVendedor::where('vendedor_id', $user->id)
                                ->with('gerente')
                                ->first()?->gerente;

                            return $gerente ? [$gerente->id => $gerente->name] : [];
                        }

                        // Caso contrário, lista todos os gerentes
                        return User::whereRelation('role', 'name', 'Gerente Comercial')->pluck('name', 'id')->toArray();
                    })
                    ->default(function () {
                        $user = auth()->user();

                        if ($user?->role?->name === 'Vendedor') {
                            return \App\Models\GerenteVendedor::where('vendedor_id', $user->id)->value('gerente_id');
                        }

                        return null;
                    })
                    ->disabled(fn() => auth()->user()?->role?->name === 'Vendedor')
                    ->searchable()
                    ->required()
                    //->default(1)
                    ->dehydrated(),
            ])
            ->columns(3);
    }
}
