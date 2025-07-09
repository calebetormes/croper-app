{{-- resources/views/reports/partials/produtos.blade.php --}}
<section class="mb-6">
    <h3>Produtos</h3>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border:1px solid #ccc; padding:4px; text-align:left;">Produto</th>
                <th style="border:1px solid #ccc; padding:4px; text-align:right;">Volume</th>
                <th style="border:1px solid #ccc; padding:4px; text-align:right;">Preço Unit. (R$)</th>
                <th style="border:1px solid #ccc; padding:4px; text-align:right;">Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->negociacaoProdutos as $item)
                <tr>
                    <td style="border:1px solid #ccc; padding:4px;">{{ $item->produto->nome_composto }}</td>
                    <td style="border:1px solid #ccc; padding:4px; text-align:right;">
                        {{ number_format($item->volume, 2, ',', '.') }}</td>
                    <td style="border:1px solid #ccc; padding:4px; text-align:right;">
                        {{ number_format($item->snap_produto_preco_rs, 2, ',', '.') }}</td>
                    <td style="border:1px solid #ccc; padding:4px; text-align:right;">
                        {{ number_format($item->total_preco_rs, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>