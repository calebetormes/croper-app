<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use App\Models\Cultura;
use App\Models\PracaCotacao;
use Carbon\Carbon;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\NegociacaoResource\Logic\PrecoLiquidoSacaLogic;


class CotacoesSectionForm
{
    public static function make(): Section
    {
        return Section::make('Cotações')
            ->schema([
                // 1) Cultura
                ToggleButtons::make('cultura_id')
                    ->label('Cultura')
                    ->options(Cultura::pluck('nome', 'id')->toArray())
                    ->required()
                    ->inline()
                    ->reactive(),

                // 2) Cidade
                Select::make('praca_cotacao_cidade')
                    ->label('Cidade')
                    ->reactive()
                    ->options(fn(Get $get) => self::getCityOptions($get))
                    ->afterStateHydrated(function (Get $get, Set $set, $state) {
                        if (!$state && $get('praca_cotacao_id')) {
                            $cot = PracaCotacao::find($get('praca_cotacao_id'));
                            $set('praca_cotacao_cidade', $cot->cidade ?? null);
                        }
                    })
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::resetOnCityChange($set))
                    ->required(),

                // 3) Vencimento
                Select::make('praca_cotacao_id')
                    ->label('Vencimento')
                    ->reactive()
                    ->searchable()
                    ->options(fn(Get $get) => self::getPracaOptions($get))
                    ->getOptionLabelUsing(
                        fn($value) =>
                        $value
                        ? Carbon::parse(PracaCotacao::find($value)->data_vencimento)->format('d/m/Y')
                        : null
                    )
                    ->disabled(fn(Get $get) => !$get('praca_cotacao_cidade'))
                    ->required()
                    ->afterStateUpdated(fn($state, Set $set) => self::handlePracaSelection($state, $set)),

                // 4) Preço da Praça
                TextInput::make('snap_praca_cotacao_preco')
                    ->label('Preço da Praça')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->dehydrated()
                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                        // 1) Mantém o valor bruto no preco_liquido_saca
                        $set('preco_liquido_saca', $state);
                        // 2) Recalcula já com o índice de valorização
                        PrecoLiquidoSacaLogic::updatePrecoLiquidoSaca($get, $set);
                    })
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        // ignora o $state cru e chama sempre a lógica completa
                        PrecoLiquidoSacaLogic::updatePrecoLiquidoSaca($get, $set);
                    })
                    ->hidden(fn() => in_array(Auth::user()->role_id, [1, 2])),

                Hidden::make('snap_praca_cotacao_preco')
                    ->dehydrated()
                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                        // 1) Mantém o valor bruto no preco_liquido_saca
                        $set('preco_liquido_saca', $state);
                        // 2) Recalcula já com o índice de valorização
                        PrecoLiquidoSacaLogic::updatePrecoLiquidoSaca($get, $set);
                    })
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        // ignora o $state cru e chama sempre a lógica completa
                        PrecoLiquidoSacaLogic::updatePrecoLiquidoSaca($get, $set);
                    }),


                // 5) Flag preço fixado
                Hidden::make('snap_praca_cotacao_preco_fixado')
                    ->default(true)
                    ->dehydrated(),

                // 6) Data fixação de preço (apenas visualização em edição)
                DatePicker::make('data_atualizacao_snap_preco_praca_cotacao')
                    ->label('Preço fixado em')
                    ->default(fn(Get $get) => $get('data_atualizacao_snap_preco_praca_cotacao'))
                    //->disabled()
                    ->reactive()
                    ->afterOrEqual(now()->subDays(3)->toDateString())
                    ->validationMessages([
                        'after_or_equal' => 'Selecione uma cotação atualizada (máx. 3 dias).',
                    ])
                    ->validationAttribute('Preço fixado em'),

                // 7) Botões de ação
                Actions::make([
                    Action::make('atualizar_preco_praca')
                        ->label('Atualizar Preço da Praça')
                        ->color('primary')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn(Get $get) => $get('praca_cotacao_id'))
                        ->action(fn(Get $get, Set $set) => self::updateToLatestPrice($get, $set)),
                ]),
            ])
            ->columns(4);
    }

    private static function getCityOptions(Get $get): array
    {
        if (!$get('cultura_id') || !$get('moeda_id')) {
            return [];
        }

        return PracaCotacao::query()
            ->where('cultura_id', $get('cultura_id'))
            ->where('moeda_id', $get('moeda_id'))
            ->whereNotNull('cidade')
            ->orderBy('data_vencimento', 'desc')
            ->pluck('cidade', 'cidade')
            ->unique()
            ->toArray();
    }

    private static function getPracaOptions(Get $get): array
    {
        if (!$get('cultura_id') || !$get('moeda_id') || !$get('praca_cotacao_cidade')) {
            return [];
        }

        return PracaCotacao::query()
            ->where('cultura_id', $get('cultura_id'))
            ->where('moeda_id', $get('moeda_id'))
            ->where('cidade', $get('praca_cotacao_cidade'))
            ->orderBy('data_vencimento', 'desc')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->id => Carbon::parse($item->data_vencimento)->format('d/m/Y'),
            ])
            ->toArray();
    }

    private static function resetOnCityChange(Set $set): void
    {
        $set('praca_cotacao_id', null);
        $set('snap_praca_cotacao_preco', null);
        $set('snap_praca_cotacao_fator_valorizacao', null);
        $set('data_atualizacao_snap_preco_praca_cotacao', null);
    }

    private static function handlePracaSelection($state, Set $set): void
    {
        $cotacao = PracaCotacao::find($state);
        $set('snap_praca_cotacao_preco', $cotacao?->praca_cotacao_preco);
        $set('data_atualizacao_snap_preco_praca_cotacao', now()->toDateString());
        $set('preco_liquido_saca', $cotacao?->praca_cotacao_preco);
    }

    private static function updateToLatestPrice(Get $get, Set $set): void
    {
        $current = PracaCotacao::find($get('praca_cotacao_id'));
        if (!$current) {
            return;
        }

        $latest = PracaCotacao::query()
            ->where('cidade', $current->cidade)
            ->where('cultura_id', $get('cultura_id'))
            ->where('moeda_id', $get('moeda_id'))
            ->orderBy('data_vencimento', 'desc')
            ->first();

        if ($latest) {
            $set('praca_cotacao_id', $latest->id);
            $set('snap_praca_cotacao_preco', $latest->praca_cotacao_preco);
            $set('data_atualizacao_snap_preco_praca_cotacao', now()->toDateString());
            //$set('preco_liquido_saca', $latest->praca_cotacao_preco);
            PrecoLiquidoSacaLogic::updatePrecoLiquidoSaca($get, $set);
        }
    }
}
