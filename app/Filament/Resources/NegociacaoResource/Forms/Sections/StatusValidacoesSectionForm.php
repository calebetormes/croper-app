<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;

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
                    ->disabled(fn (Get $get): bool =>
                        auth()->user()->role_id === 1 ||
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
                    ->disabled(fn (Get $get): bool =>
                        auth()->user()->role_id === 1 ||
                        auth()->user()->role_id < $get('nivel_validacao_id') ||
                        (auth()->user()->role_id === 4 && $get('nivel_validacao_id') === 3)
                    ),
                Placeholder::make('status_defensivos_label')
                    ->label('Quantidade Mínima de Defensivos')
                    ->content(fn (Get $get) => $get('status_defensivos') == 1 ? 'Atingido' : '⚠️ Adicione mais defensivos à sua negociação')
                    ->extraAttributes(fn (Get $get) => [
                        'class' => 'inline-block px-3 py-1 rounded-full text-sm font-medium ' . ($get('status_defensivos') == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'),]),
                Placeholder::make('status_especialidades_label')
                    ->label('Quantidade Mínima de Especialidades')
                    ->content(fn (Get $get) => $get('status_especialidades') > 3 ? '✅ Atingido' : '⚠️ Adicione mais especialidades à sua negociação')
                    ->extraAttributes(fn (Get $get) => [
                        'class' => $get('status_especialidades') > 3 ? 'text-green-500 font-semibold' : 'text-yellow-400 font-semibold',]),
            ])
            ->columns(4);
    }
}
