<?php
// app/Filament/Resources/NegociacaoResource/Infolist/Sections/PracaCotacaoInfolist.php

namespace App\Filament\Resources\NegociacaoResource\Infolist\Sections;

use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;

class PracaCotacaoInfolist
{
    public static function make(): InfolistSection
    {
        return InfolistSection::make('Praça Cotação')
            ->columns(4)
            ->schema([
                TextEntry::make('pracaCotacao.cidade')
                    ->label(''),
                TextEntry::make('cultura.nome')
                    ->label(''),
                TextEntry::make('preco_liquido_saca')
                    ->label('')
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $record->moeda_id === 1
                        ? 'R$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                        : 'US$ ' . number_format($record->preco_liquido_saca, 2, ',', '.')
                    ),
                TextEntry::make('pracaCotacao.data_vencimento')
                    ->label('')
                    ->formatStateUsing(fn($state) => date('d/m/Y', strtotime($state))),
            ]);
    }
}
