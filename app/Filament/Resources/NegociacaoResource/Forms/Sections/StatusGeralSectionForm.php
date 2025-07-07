<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use App\Models\StatusNegociacao;
use Filament\Forms\Get;
use Filament\Forms\Components\Hidden;

class StatusGeralSectionForm
{
    public static function make(): Section
    {
        return Section::make('Status Geral')

            ->schema([

                // 1) Carrega o nível já salvo no banco
                Hidden::make('nivel_validacao_id')
                    ->dehydrated(false), // não submete esse campo

                // 2) Seu ToggleButtons, agora usando apenas Get
                ToggleButtons::make('status_negociacao_id')
                    ->label('Status da Negociação')
                    ->options(
                        StatusNegociacao::where('ativo', true)
                            ->orderBy('ordem')
                            ->pluck('nome', 'id')
                            ->toArray()
                    )
                    ->colors([
                        StatusNegociacao::where('nome', 'Em análise')->value('id'),
                        StatusNegociacao::where('nome', 'Aprovado')->value('id') => 'success',
                        StatusNegociacao::where('nome', 'Não Aprovado')->value('id') => 'danger',
                        StatusNegociacao::where('nome', 'Concluído')->value('id') => 'gray',
                    ])
                    ->inline()
                    ->reactive()
                    ->default(1)
                    ->disabled(function (Get $get): bool {
                        $level = $get('nivel_validacao_id') ?? 0;
                        $role = auth()->user()->role_id;

                        // Nível 1: todo mundo pode editar
                        if ($level === 1) {
                            return false;
                        }

                        // Nível 2: apenas Gerente Comercial(2), Nacional(3) e Admin(4)
                        if ($level === 2) {
                            return !in_array($role, [2, 3, 4], true);
                        }

                        // Nível 3: apenas Nacional(3) e Admin(4)
                        if ($level === 3) {
                            return !in_array($role, [3, 4], true);
                        }

                        // Nível 4 (Admin): apenas Admin
                        return $role !== 4;
                    })
                    ->required()
                    ->dehydrated(),

                Textarea::make('observacoes')->columnSpanFull(),

            ]);

    }
}
