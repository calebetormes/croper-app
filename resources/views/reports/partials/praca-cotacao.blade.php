{{-- resources/views/reports/partials/praca-cotacao.blade.php --}}
<section class="mb-6">
    <h3>Praça / Cotação</h3>
    <div class="info-grid">
        <div class="item">
            <strong>Praça</strong>
            {{ $record->pracaCotacao->cidade }}
        </div>
        <div class="item">
            <strong>Cultura</strong>
            {{ $record->cultura->nome }}
        </div>
        <div class="item">
            <strong>Preço Líquido/Saca</strong>
            @if ($record->moeda_id === 1)
                R$ {{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
            @else
                US$ {{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
            @endif
        </div>
        <div class="item">
            <strong>Vencimento</strong>
            {{ \Carbon\Carbon::parse($record->pracaCotacao->data_vencimento)->format('d/m/Y') }}
        </div>
    </div>
</section>