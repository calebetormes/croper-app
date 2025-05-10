<?php

namespace App\Filament\Resources\ProdutoClasseResource\Pages;

use App\Filament\Resources\ProdutoClasseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProdutoClasses extends ManageRecords
{
    protected static string $resource = ProdutoClasseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
