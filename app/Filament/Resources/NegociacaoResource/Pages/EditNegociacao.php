<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $produtos = $data['produtos'] ?? [];
        unset($data['produtos']);

        $record = parent::handleRecordUpdate($record, $data);

        // sincroniza pivot
        $record->produtos()->sync($produtos);

        // 3) Monta o array de sync com defaults + snapshot de preços
        $syncData = [];
        foreach ($produtos as $produtoId) {
            $p = Produto::find($produtoId);

            $syncData[$produtoId] = [
                'volume' => 0,
                'potencial_produto' => 0,
                'dose_hectare' => 0,
                'snap_produto_preco_real_rs' => $p->preco_real_rs,
                'snap_produto_preco_real_us' => $p->preco_real_us,
                'snap_produto_preco_virtual_rs' => $p->preco_virtual_rs,
                'snap_produto_preco_virtual_us' => $p->preco_virtual_us,
                'snap_precos_fixados' => 1,
                'data_atualizacao_snap_precos_produtos' => now()->toDateString(),
            ];
        }

        return $record;
    }
}
