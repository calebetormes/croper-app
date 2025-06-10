<?php

namespace App\Filament\Resources\NegociacaoResource\Pages;

use App\Models\Moeda;
use App\Filament\Resources\NegociacaoResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use Filament\Actions\DeleteAction;

class EditNegociacao extends EditRecord
{
    protected static string $resource = NegociacaoResource::class;

    // Cabeçalho
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // Popula os totais INICIAIS sem apagar nada
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $produtos = $this->record->negociacaoProdutos()->get();

        return array_merge($data, [
            'valor_total_sem_bonus_rs' => $produtos->sum('total_sem_bonus_rs'),
            'valor_total_sem_bonus_us' => $produtos->sum('total_sem_bonus_us'),
            'valor_total_com_bonus_rs' => $produtos->sum('total_com_bonus_rs'),
            'valor_total_com_bonus_us' => $produtos->sum('total_com_bonus_us'),
            'investimento_total_sacas' => $this->calculateInvestimento($data),
        ]);
    }

    // Recalcula **só** quando o RelationManager emitir o evento
    #[On('negociacaoProdutoUpdated')]
    public function refreshValores(): void
    {
        $state = $this->form->getState();
        $produtos = $this->record->negociacaoProdutos()->get();

        $updates = [
            'valor_total_sem_bonus_rs' => $produtos->sum('total_sem_bonus_rs'),
            'valor_total_sem_bonus_us' => $produtos->sum('total_sem_bonus_us'),
            'valor_total_com_bonus_rs' => $produtos->sum('total_com_bonus_rs'),
            'valor_total_com_bonus_us' => $produtos->sum('total_com_bonus_us'),
            'investimento_total_sacas' => $this->calculateInvestimento($state),
        ];

        // Merge preserve os outros campos
        $this->form->fill(array_merge($state, $updates));
    }

    // Extrai a lógica de investimento para facilitar reuse
    protected function calculateInvestimento(array $state): float
    {
        $sigla = optional(Moeda::find($state['moeda_id'] ?? null))->sigla;
        $total = $sigla === 'US$'
            ? ($state['valor_total_sem_bonus_us'] ?? 0)
            : ($state['valor_total_sem_bonus_rs'] ?? 0);
        $preco = $state['preco_liquido_saca'] ?: 1;

        return $total / $preco;
    }

    public function updatedSnapPracaCotacaoPreco($value): void
    {
        // 1) garantir que o preco_liquido_saca esteja sincronizado
        $state = $this->form->getState();
        $state['preco_liquido_saca'] = $value;

        // 2) recalcula o investimento
        $state['investimento_total_sacas'] = $this->calculateInvestimento($state);

        // 3) preenche de volta no form sem apagar nada
        $this->form->fill($state);
    }

    // Sempre que o preço mudar, atualiza só o investimento
    public function updatedPrecoLiquidoSaca($value): void
    {
        $state = $this->form->getState();
        $state['investimento_total_sacas'] = $this->calculateInvestimento($state);
        $this->form->fill($state);
    }
}
