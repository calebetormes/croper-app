{{-- resources/views/reports/partials/status.blade.php --}}
<section class="mb-6">
    <h3>Status</h3>
    <div class="info-grid">
        <div class="item">
            <strong>Status Negociação</strong>
            {{ $record->statusNegociacao->nome }}
        </div>
        <div class="item">
            <strong>Nível Validação</strong>
            {{ $record->nivelValidacao->nome }}
        </div>
        <div class="item">
            <strong>Observações</strong>
            {{ $record->observacoes }}
        </div>
    </div>
</section>