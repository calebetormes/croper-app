<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use Filament\Infolists\Infolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\DadosBasicosInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\PracaCotacaoInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\ValoresInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\ProdutosInfolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\StatusInfolist;



class InfolistSchema
{
    public static function make(Infolist $infolist): Infolist
    {
        //$infolist->getRecord();

        return $infolist
            ->name('relatorio_negociacao')
            ->columns(2)
            ->schema([
                DadosBasicosInfolist::make(),
                PracaCotacaoInfolist::make(),
                ValoresInfolist::make(),
                ProdutosInfolist::make(),
                StatusInfolist::make(),
            ]);
    }
}
