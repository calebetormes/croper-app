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
                TextInput::make('valor_total_com_bonus_rs')
                    ->label('Valor Total com Bônus R$')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix('R$')
                    ->dehydrated(),

                TextInput::make('valor_total_com_bonus_us')
                    ->label('Valor Total com Bônus U$')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix('US$')
                    ->dehydrated(),



                TextInput::make('valor_total_sem_bonus_rs')
                    ->label('Valor Total sem Bônus R$')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix('R$')
                    ->dehydrated(),

                TextInput::make('valor_total_sem_bonus_us')
                    ->label('Valor Total sem Bônus U$')
                    ->reactive()             // ⬅️ adiciona reatividade
                    ->disabled()
                    ->numeric()
                    ->default(0)
                    ->prefix('US$')
                    ->dehydrated(),

                TextInput::make('valor_total_com_bonus_sacas')->numeric(),
                TextInput::make('valor_total_sem_bonus_sacas')->numeric(),
                TextInput::make('peso_total_kg')->numeric(),
                TextInput::make('investimento_sacas_hectare')->numeric(),
                TextInput::make('investimento_total_sacas')->numeric(),
                TextInput::make('preco_liquido_saca')->numeric(),
                TextInput::make('bonus_cliente_pacote')->numeric(),

            ])
            ->columns(2);
    }
}
