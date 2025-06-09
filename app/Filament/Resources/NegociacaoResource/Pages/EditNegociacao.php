<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Livewire\Attributes\On;



class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    // cabeçalho: botão de excluir
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // 1) Registra o listener
    protected function getListeners(): array
    {
        return array_merge(parent::getListeners(), [
            'negociacaoProdutoUpdated' => 'refreshValores',
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // busca sempre pela relação real, ignorando casts indesejados
        $produtos = $this->record->negociacaoProdutos()->get();

        return array_merge($data, [
            'valor_total_sem_bonus_rs' => $produtos->sum(fn($item) => $item->snap_produto_preco_rs * $item->volume),
            'valor_total_sem_bonus_us' => $produtos->sum(fn($item) => $item->snap_produto_preco_us * $item->volume),
            'valor_total_com_bonus_rs' => $produtos->sum(fn($item) => $item->negociacao_produto_preco_virtual_rs * $item->volume),
            'valor_total_com_bonus_us' => $produtos->sum(fn($item) => $item->negociacao_produto_preco_virtual_us * $item->volume),
        ]);
    }

    #[On('negociacaoProdutoUpdated')]
    public function refreshValores(): void
    {
        $produtos = $this->record->negociacaoProdutos()->get();

        $novosTotais = [
            'valor_total_sem_bonus_rs' => $produtos->sum(fn($item) => $item->snap_produto_preco_rs * $item->volume),
            'valor_total_sem_bonus_us' => $produtos->sum(fn($item) => $item->snap_produto_preco_us * $item->volume),
            'valor_total_com_bonus_rs' => $produtos->sum(fn($item) => $item->negociacao_produto_preco_virtual_rs * $item->volume),
            'valor_total_com_bonus_us' => $produtos->sum(fn($item) => $item->negociacao_produto_preco_virtual_us * $item->volume),
        ];

        // preserva todo o state atual e sobrescreve só os 4 campos
        $state = $this->form->getState();
        $newState = array_merge($state, $novosTotais);

        $this->form->fill($newState);
    }

}