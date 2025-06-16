<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Models\StatusNegociacao;
use Filament\Forms\Get;

class StatusGeralSectionForm
{
    public static function make(): Section
    {
        return Section::make('Status Geral')

            ->schema([
                ToggleButtons::make('status_negociacao_id')
                    ->label('Status da Negociação')
                    ->options(StatusNegociacao::where('ativo', true)->orderBy('ordem')->pluck('nome', 'id')->toArray())
                    ->colors([
                        StatusNegociacao::where('nome', 'Em análise')->value('id') => 'warning',
                        StatusNegociacao::where('nome', 'Aprovado')->value('id') => 'success',
                        StatusNegociacao::where('nome', 'Não Aprovado')->value('id') => 'danger',
                        StatusNegociacao::where('nome', 'Pausada')->value('id') => 'warning',
                        StatusNegociacao::where('nome', 'Pagamento Recebido')->value('id') => 'success',
                        StatusNegociacao::where('nome', 'Entrega de Grãos Realizada')->value('id') => 'success',
                        StatusNegociacao::where('nome', 'Concluído')->value('id') => 'gray',
                    ])
                    ->inline()
                    ->default(StatusNegociacao::where('nome', 'Em análise')->value('id')) // <- Default aplicado
                    ->dehydrated(true) // <- Garante que será enviado mesmo se desabilitado
                    ->required()
                    ->default(StatusNegociacao::where('nome', 'Em análise')->value('id'))
                    ->reactive()
                    ->disabled(fn() => !in_array(auth()->user()?->role_id, [3, 4])),
                Textarea::make('observacoes')->columnSpanFull(),
                Select::make('nivel_validacao_id')
                    ->label('Que poderá validar')
                    ->options([1 => 'Vendedor', 2 => 'Gerente Comercial', 3 => 'Gerente Nacional', 4 => 'Admin'])
                    ->default(4)
                    ->searchable()
                    ->required()
                    ->disabled(
                        fn(Get $get): bool => auth()->user()->role_id === 1 ||
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
                    ->disabled(
                        fn(Get $get): bool => auth()->user()->role_id === 1 ||
                        auth()->user()->role_id < $get('nivel_validacao_id') ||
                        (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                    ),
            ]);

    }
}
