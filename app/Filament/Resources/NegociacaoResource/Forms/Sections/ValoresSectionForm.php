<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use App\Models\Moeda;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                TextInput::make('valor_total_com_bonus')
                    ->label('Valor Total com Bônus')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix(fn($get) => optional(Moeda::find($get('moeda_id')))->sigla)
                    ->dehydrated(),

                TextInput::make('valor_total_sem_bonus')
                    ->label('Valor Total sem Bônus')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix(fn($get) => optional(Moeda::find($get('moeda_id')))->sigla)
                    ->dehydrated(),

                TextInput::make('investimento_sacas_hectare')->numeric(),
                TextInput::make('investimento_total_sacas')->numeric(),
                TextInput::make('preco_liquido_saca')->numeric(),
                TextInput::make('bonus_cliente_pacote')->numeric(),

            ])
            ->columns(2);
    }
}
