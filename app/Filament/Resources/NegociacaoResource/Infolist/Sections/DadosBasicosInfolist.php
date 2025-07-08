<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/DadosBasicosInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;

class DadosBasicosInfolist
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Dados Básicos')
            ->columns(4)
            ->schema([
                TextEntry::make('pedido_id')
                    ->label('Pedido ID'),
                TextEntry::make('data_negocio')
                    ->label('Data da Negociação')
                    ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
                TextEntry::make('gerente_id')
                    ->label('Gerente')
                    ->formatStateUsing(fn($state, $record) => optional($record->gerente)->name ?? '—'),
                TextEntry::make('vendedor_id')
                    ->label('Vendedor')
                    ->formatStateUsing(fn($state, $record) => optional($record->vendedor)->name ?? '—'),
                TextEntry::make('cliente')
                    ->label('Cliente'),
                TextEntry::make('area_hectares')
                    ->label('Área em Hectares')
                    ->formatStateUsing(fn($state) => number_format($state, 2, ',', '.')),
                TextEntry::make('moeda.sigla')
                    ->label('Moeda'),
            ]);
    }
}
