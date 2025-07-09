{{-- resources/views/reports/partials/valores.blade.php --}}
<section class="mb-6">
    <h3>Valores</h3>
    <div class="info-grid">
        <div class="item">
            <strong>Investimento Total (sacas)</strong>
            {{ number_format($record->investimento_total_sacas, 0, ',', '.') }}
        </div>
        <div class="item">
            <strong>Valor Total (R$)</strong>
            R$ {{ number_format($record->valor_total_pedido_rs, 2, ',', '.') }}
        </div>
        <div class="item">
            <strong>Margem Faturamento (R$)</strong>
            R$ {{ number_format($record->margem_faturamento_total_rs, 2, ',', '.') }}
        </div>
        <div class="item">
            <strong>Margem (%)</strong>
            {{ number_format($record->margem_percentual_total_rs, 2, ',', '.') }}%
        </div>
        <div class="item">
            <strong>Peso Total (kg)</strong>
            {{ number_format($record->peso_total_kg, 0, ',', '.') }}
        </div>
    </div>
</section>