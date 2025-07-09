{{-- resources/views/reports/partials/dados-basicos.blade.php --}}
<section class="mb-6">
    <h3>Dados Básicos</h3>
    <div class="info-grid">
        <div class="item">
            <strong>Data do negócio</strong>
            {{ $record->data_negocio->format('d/m/Y') }}
        </div>
        <div class="item">
            <strong>Cliente</strong>
            {{ $record->cliente }}
        </div>
        <div class="item">
            <strong>Endereço</strong>
            {{ $record->endereco_cliente }}, {{ $record->cidade_cliente }}
        </div>
        <div class="item">
            <strong>Moeda</strong>
            {{ $record->moeda->sigla }}
        </div>
        <div class="item">
            <strong>Gerente</strong>
            {{ $record->gerente->name }}
        </div>
        <div class="item">
            <strong>Vendedor</strong>
            {{ $record->vendedor->name }}
        </div>
    </div>
</section>