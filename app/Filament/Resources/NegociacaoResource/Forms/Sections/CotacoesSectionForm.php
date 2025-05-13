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
                Select::make('praca_cotacao_id')
                    ->label('Praça')
                    ->reactive()
                    ->options(fn ($get) => Cultura::find($get('cultura_id'))?->pracasCotacao->whereNotNull('cidade')->pluck('cidade', 'id')->toArray() ?? [])
                    ->disabled(fn ($get) => ! $get('cultura_id'))
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function ($state, $set) {
                        $cotacao = PracaCotacao::find($state);
                        $data   = $cotacao && $cotacao->data_vencimento
                            ? Carbon::parse($cotacao->data_vencimento)->format('d/m/Y')
                            : null;
                        $set('data_praca_vencimento', $data);
                        $set('snap_praca_cotacao_preco', $cotacao?->praca_cotacao_preco);
                    }),
                TextInput::make('snap_praca_cotacao_preco')
                    ->label('Preço da Praça')
                    ->numeric()
                    ->required()
                    ->dehydrated()
                    ->reactive(),
                Placeholder::make('data_praca_vencimento')
                    ->label('Data da Cotação')
                    ->content(fn ($get) => $get('praca_cotacao_id')
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
                        ->visible(fn ($get) => $get('praca_cotacao_id'))
                        ->action(function ($get, $set) {
                            $cotacao = PracaCotacao::find($get('praca_cotacao_id'));
                            $set('snap_praca_cotacao_preco', $cotacao?->praca_cotacao_preco);
                            $set('data_atualizacao_snap_preco_praca_cotacao', date('Y-m-d'));
                        }),
                ]),
                DatePicker::make('data_atualizacao_snap_preco_praca_cotacao')
                    ->label('Preço fixado no dia')
                    ->disabled()
                    ->dehydrated()
                    ->reactive(),
            ])
            ->columns(4);
    }
}
