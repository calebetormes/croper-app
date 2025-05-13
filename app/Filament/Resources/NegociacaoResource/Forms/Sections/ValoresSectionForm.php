<?php

namespace App\Filament\Resources\NegociacaoResource\Forms\Sections;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class ValoresSectionForm
{
    public static function make(): Section
    {
        return Section::make('Valores')
            ->schema([
                TextInput::make('valor_total_com_bonus')->numeric()->required(),
                TextInput::make('investimento_sacas_hectare')->numeric(),
                TextInput::make('investimento_total_sacas')->numeric(),
                TextInput::make('preco_liquido_saca')->numeric(),
                TextInput::make('bonus_cliente_pacote')->numeric(),
                TextInput::make('valor_total_sem_bonus')->numeric(),
            ])
            ->columns(4);
    }
}
