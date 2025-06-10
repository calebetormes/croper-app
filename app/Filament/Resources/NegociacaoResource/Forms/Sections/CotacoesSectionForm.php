<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use App\Models\Cultura;
use App\Models\PracaCotacao;
use Carbon\Carbon;

class CotacoesSectionForm
{
    public static function make(): Section
    {
        return Section::make('Cotações')
            ->schema([
                ToggleButtons::make('cultura_id')
                    ->label('Cultura')
                    ->options(Cultura::pluck('nome', 'id')->toArray())
                    ->required()
                    ->inline()
                    ->reactive(),

                DatePicker::make('data_atualizacao_snap_preco_praca_cotacao')
                    ->label('Preço fixado no dia')
                    ->default(fn() => now()->toDateString())
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->afterOrEqual(now()->subDays(3)->toDateString())
                    ->validationMessages([
                        'after_or_equal' => 'Já se passaram {$days} dias desde a última atualização de preços da praça. Selecione a praça atualizada',
                    ])
                    ->validationAttribute('Preço fixado no dia')
                    ->afterStateHydrated(function ($component, $state) {
                        // $state virá como string “YYYY-MM-DD” vinda do banco
                        if ($state) {
                            $selectedDate = Carbon::parse($state);
                            $limite = Carbon::now()->subDays(3)->startOfDay();

                            if ($selectedDate->lt($limite)) {
                                // Se a data for anterior a (hoje − 3 dias),
                                // limpamos imediatamente o campo praca_cotacao_id no form
                                $component
                                    ->getLivewire()
                                    ->fill([
                                        'praca_cotacao_id' => null,
                                    ]);
                            }
                        }
                    }),

                Select::make('praca_cotacao_id')
                    ->label('Praça')
                    ->reactive()

                    ->options(function ($get) {
                        // Se não houver cultura ou moeda selecionada, retorna vazio
                        if (!$get('cultura_id') || !$get('moeda_id')) {
                            return [];
                        }


                        return PracaCotacao::query()
                            ->where('cultura_id', $get('cultura_id'))
                            ->where('moeda_id', $get('moeda_id'))
                            ->whereNotNull('cidade')
                            ->orderBy('data_vencimento', 'desc')
                            ->get()
                            ->unique('cidade')
                            ->mapWithKeys(function ($item) {
                                $formattedDate = Carbon::parse($item->data_vencimento)->format('d/m/Y');
                                return [
                                    //$item->id => "{$item->cidade} – {$formattedDate}",
                                    $item->id => "{$item->cidade}",
                                ];
                            })
                            ->toArray();
                    })

                    // <-- aqui, adicionamos o getOptionLabelUsing:
                    ->getOptionLabelUsing(function ($value) {
                        if (!$value) {
                            return null;
                        }
                        $cotacao = PracaCotacao::find($value);
                        if (!$cotacao) {
                            return null;
                        }
                        $data = Carbon::parse($cotacao->data_vencimento)->format('d/m/Y');
                        return "{$cotacao->cidade} – {$data}";
                    })

                    ->disabled(fn($get) => !$get('cultura_id') || !$get('moeda_id'))
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function ($state, $set) {
                        $cotacao = PracaCotacao::find($state);
                        $preco = $cotacao?->praca_cotacao_preco;
                        $data = $cotacao && $cotacao->data_vencimento
                            ? Carbon::parse($cotacao->data_vencimento)->format('d/m/Y')
                            : null;
                        $set('data_praca_vencimento', $data);
                        $set('snap_praca_cotacao_preco', $cotacao?->praca_cotacao_preco);
                        $set('snap_praca_cotacao_fator_valorizacao', $cotacao?->fator_valorizacao);
                        $set('data_atualizacao_snap_preco_praca_cotacao', date('Y-m-d'));
                        $set('preco_liquido_saca', $preco);
                    }),

                TextInput::make('snap_praca_cotacao_preco')
                    ->label('Preço da Praça')
                    ->numeric()
                    ->required()
                    ->dehydrated()
                    ->reactive()
                    ->afterStateHydrated(fn($state, callable $set) => $set('preco_liquido_saca', $state))
                    // Sempre que mudar aqui, replica lá
                    ->afterStateUpdated(fn($state, callable $set) => $set('preco_liquido_saca', $state)),

                TextInput::make('snap_praca_cotacao_fator_valorizacao')
                    ->label('Fator de Valorização')
                    ->numeric()
                    ->required()
                    ->dehydrated()
                    ->reactive(),

                Placeholder::make('data_praca_vencimento')
                    ->label('Data da Cotação')
                    ->content(
                        fn($get) => $get('praca_cotacao_id')
                        ? Carbon::parse(PracaCotacao::find($get('praca_cotacao_id'))->data_vencimento)->format('d/m/Y')
                        : 'Nenhuma cotação selecionada'
                    )
                    ->reactive(),

                Hidden::make('snap_praca_cotacao_preco_fixado')->default(true)->dehydrated(),

                Actions::make([
                    Action::make('atualizar_preco_praca')
                        ->label('Atualizar Preço da Praça')
                        ->color('primary')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn($get) => $get('praca_cotacao_id'))
                        ->action(function ($get, $set) {
                            // 1) Cotação atual selecionada
                            $current = PracaCotacao::find($get('praca_cotacao_id'));
                            if (!$current) {
                                return;
                            }

                            $cidade = $current->cidade;
                            $cultura_id = $get('cultura_id');
                            $moeda_id = $get('moeda_id');

                            // 2) Busca a cotação MAIS RECENTE (data_vencimento mais alta) para a mesma cidade / cultura / moeda
                            $nova = PracaCotacao::query()
                                ->where('cidade', $cidade)
                                ->where('cultura_id', $cultura_id)
                                ->where('moeda_id', $moeda_id)
                                ->whereNotNull('data_vencimento')
                                ->orderBy('data_vencimento', 'desc')
                                ->first();

                            if (!$nova) {
                                return;
                            }

                            // 3) Atribui o novo ID ao select
                            $set('praca_cotacao_id', $nova->id);

                            // 4) Atualiza os campos “snap_...” com base nessa nova cotação
                            $set('snap_praca_cotacao_preco', $nova->praca_cotacao_preco);
                            $set('snap_praca_cotacao_fator_valorizacao', $nova->fator_valorizacao);

                            // 5) Marca a data de atualização como hoje
                            $set('data_atualizacao_snap_preco_praca_cotacao', date('Y-m-d'));

                        }),
                ]),
            ])
            ->columns(3);
    }
}
