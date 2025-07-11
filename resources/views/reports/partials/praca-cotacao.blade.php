{{-- resources/views/reports/partials/praca-cotacao.blade.php --}}
<section class="section">
    <h3>Praça / Cotação</h3>
    <div class="info-grid">
        <div class="item">
            <strong>Praça:</strong><br>
            {{ $record->pracaCotacao->cidade ?? '–' }}
        </div>
        <div class="item">
            <strong>Vencimento:</strong><br>
            {{ optional($record->pracaCotacao->data_vencimento)->format('d/m/Y') ?? '–' }}
        </div>
        <div class="item">
            <strong>Snapshot Preço:</strong><br>
            {{ number_format($record->snap_praca_cotacao_preco, 2, ',', '.') }} {{ $record->moeda->sigla }}
        </div>
        <div class="item">
            <strong>Atualização Snapshot:</strong><br>
            {{ optional($record->data_atualizacao_snap_preco_praca_cotacao)->format('d/m/Y') ?? '–' }}
        </div>
        <div class="item">
            <strong>Preço Líquido/Saca:</strong><br>
            @if($record->moeda_id === 1)
                R${{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
            @else
                US${{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
            @endif
        </div>
    </div>
</section>