<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Relatório de Negociação #{{ $record->pedido_id }}</title>
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
</head>

<body>
    <header>
        <h1>Relatório de Negociação</h1>
        <p>Emitido em: {{ now()->format('d/m/Y H:i') }}</p>
    </header>

    @include('reports.partials.dados-basicos', ['record' => $record])
    @include('reports.partials.praca-cotacao', ['record' => $record])

    {{-- Produtos negociados --}}
    <section class="section">
        <h3>Produtos</h3>
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Volume</th>
                    <th>Preço R$</th>
                    <th>Preço US$</th>
                    <th>Margem %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->negociacaoProdutos as $item)
                    <tr>
                        <td>{{ $item->produto->nome ?? '—' }}</td>
                        <td>{{ number_format($item->volume, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->snap_produto_preco_rs, 2, ',', '.') }}</td>
                        <td>US$ {{ number_format($item->snap_produto_preco_us, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->margem_percentual_rs, 2, ',', '.') }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</body>

</html>