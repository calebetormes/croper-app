{{-- resources/views/reports/partials/dados-basicos.blade.php --}}
<section class="section">
    <h3>Dados Básicos</h3>

    <div class="info-grid">
        <div class="item">
            <strong>Pedido:</strong>
            #{{ $record->pedido_id }}
        </div>
        <div class="item">
            <strong>Versão:</strong>
            {{ optional($record->data_versao)->format('d/m/Y') ?? '–' }}
        </div>
        <div class="item">
            <strong>Negociação:</strong>
            {{ optional($record->data_negocio)->format('d/m/Y') ?? '–' }}
        </div>
        <div class="item">
            <strong>Cliente:</strong>
            {{ $record->cliente }}
        </div>
        <div class="item">
            <strong>Endereço:</strong>
            {{ $record->endereco_cliente }}, {{ $record->cidade_cliente }}
        </div>
        <div class="item">
            <strong>Moeda:</strong>
            {{ $record->moeda->sigla }}
        </div>
        <div class="item">
            <strong>Gerente:</strong>
            {{ $record->gerente->name }}
        </div>
        <div class="item">
            <strong>Vendedor:</strong>
            {{ $record->vendedor->name }}
        </div>
        <div class="item">
            <strong>Cultura:</strong>
            {{ $record->cultura->nome }}
        </div>
        <div class="item">
            <strong>Pagamento:</strong>
            {{ $record->pagamento->nome ?? '–' }}
        </div>
        <div class="item">
            <strong>Entrega Grãos:</strong>
            {{ optional($record->data_entrega_graos)->format('d/m/Y') ?? '–' }}
        </div>
        <div class="item">
            <strong>Câmbio USD/BRL:</strong>
            {{ number_format($record->cotacao_moeda_usd_brl, 2, ',', '.') }}
        </div>
    </div>
</section>