<section class="section">
    <h3>Dados Básicos</h3>
    <p><strong>Pedido:</strong> #{{ $record->pedido_id }}</p>
    <p><strong>Cliente:</strong> {{ $record->cliente }}</p>
    <p><strong>Endereço:</strong> {{ $record->endereco_cliente }}, {{ $record->cidade_cliente }}</p>
    <p><strong>Moeda:</strong> {{ $record->moeda->sigla ?? '–' }}</p>
    <p><strong>Gerente:</strong> {{ $record->gerente->name ?? '–' }}</p>
    <p><strong>Vendedor:</strong> {{ $record->vendedor->name ?? '–' }}</p>
    <p><strong>Cultura:</strong> {{ $record->cultura->nome ?? '–' }}</p>
    <p><strong>Pagamento:</strong> {{ $record->pagamento->nome ?? '–' }}</p>
    <p><strong>Entrega de Grãos:</strong> {{ optional($record->data_entrega_graos)->format('d/m/Y') ?? '–' }}</p>
</section>