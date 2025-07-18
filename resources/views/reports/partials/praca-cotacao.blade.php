<section class="section">
    <h3>Praça / Cotação</h3>
    <p><strong>Praça:</strong> {{ $record->pracaCotacao->cidade ?? '–' }}</p>
    <p><strong>Vencimento:</strong> {{ optional($record->pracaCotacao->data_vencimento)->format('d/m/Y') ?? '–' }}</p>
    <p><strong>Preço Praça (Snapshot):</strong> {{ number_format($record->snap_praca_cotacao_preco, 2, ',', '.') }}
        {{ $record->moeda->sigla }}
    </p>
    <p><strong>Atualização Snapshot:</strong>
        {{ optional($record->data_atualizacao_snap_preco_praca_cotacao)->format('d/m/Y') ?? '–' }}</p>
    <p><strong>Preço Líquido por Saca:</strong>
        @if($record->moeda_id === 1)
            R$ {{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
        @else
            US$ {{ number_format($record->preco_liquido_saca, 2, ',', '.') }}
        @endif
    </p>
</section>