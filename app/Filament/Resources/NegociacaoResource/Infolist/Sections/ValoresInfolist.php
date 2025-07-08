<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/ValoresInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;

class ValoresInfolist
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Valores')
            ->columns(3)
            ->schema([
                TextEntry::make('investimento_total_sacas')
                    ->label('Total de Sacas')
                    ->formatStateUsing(fn($state) => (int) round($state))
                    ->suffix(' sacas'),
                TextEntry::make('valor_total_pedido_rs')
                    ->label('Total Pedido')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->moeda_id === 1
                        ? 'R$ ' . number_format($state, 2, ',', '.')
                        : 'US$ ' . number_format($record->valor_total_pedido_us, 2, ',', '.')
                    ),
                TextEntry::make('margem_faturamento_total_rs')
                    ->label('Margem Obtida')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->moeda_id === 1
                        ? 'R$ ' . number_format($state, 2, ',', '.')
                        : 'US$ ' . number_format($record->margem_faturamento_total_us, 2, ',', '.')
                    ),
                TextEntry::make('margem_percentual_total_rs')
                    ->label('Margem (%)')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->moeda_id === 1
                        ? 'R$ ' . number_format($state, 2, ',', '.')
                        : 'US$ ' . number_format($record->margem_percentual_total_us, 2, ',', '.')
                    ),
                TextEntry::make('peso_total_kg')
                    ->label('Peso Total (kg)')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
            ]);
    }
}
