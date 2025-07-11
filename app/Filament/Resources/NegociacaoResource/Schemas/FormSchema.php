<?php

namespace App\Filament\Resources\NegociacaoResource\Schemas;

use App\Filament\Resources\NegociacaoResource\Forms\Sections\BasicInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ClientInformationSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\CotacoesSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\PagamentosSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\StatusGeralSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ValoresSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\NegociacaoProdutoSectionForm;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\QuantidadeMinimaSectionForm;
use Filament\Forms\Components\Tabs;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ValoresSectionVendedor;
use App\Filament\Resources\NegociacaoResource\Forms\Sections\ValoresSectionRestricted;



class FormSchema
{
    public static function make(): array
    {
        return [
            Tabs::make('Negociação')
                ->tabs([
                    Tabs\Tab::make('Informações Básicas')
                        ->schema([
                            BasicInformationSectionForm::make(),
                            ClientInformationSectionForm::make(),
                            PagamentosSectionForm::make(),
                            CotacoesSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Produtos')
                        ->disabled(fn(?Model $record) => !$record?->exists)
                        ->dehydrated()
                        ->schema([
                            NegociacaoProdutoSectionForm::make(),
                            QuantidadeMinimaSectionForm::make(),
                        ]),

                    Tabs\Tab::make('Valores e Status')
                        ->disabled(fn(?Model $record) => !$record?->exists)
                        ->dehydrated()
                        ->schema([

                            ValoresSectionVendedor::make(),
                            ValoresSectionRestricted::make(),
                            StatusGeralSectionForm::make(),
                        ]),


                ])
                ->columnSpanFull(),
        ];
    }
}
