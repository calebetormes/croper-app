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

    {{-- Cabeçalho com logo e título --}}
    <header>
        <div style="display: table; width: 100%; margin: 0 auto;">
            {{-- Coluna 1: Logo --}}
            <div style="display: table-cell; width: 25%; vertical-align: middle; text-align: left;">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo Croper" height="40"
                    style="display: block; margin: 0 auto; padding: 0; vertical-align: middle;">
            </div>

            {{-- Coluna 2: Dados da empresa --}}
            <div
                style="display: table-cell; width: 45%; vertical-align: middle; font-size: 11px; line-height: 1.4; text-align: left;">
                <strong>CROPFIELD DO BRASIL S.A</strong><br>
                R FLORENÇA, LOTE 09/10, 0 - JARDIM ITÁLIA<br>
                Campo Novo do Parecis/MT CEP 78360-000<br>
                CNPJ: 17.605.035/0006-61 I.E.: 136436170
            </div>

            {{-- Coluna 3: Proposta de Venda --}}
            <div
                style="display: table-cell; width: 30%; vertical-align: middle; text-align: right; font-size: 11px; line-height: 1.6;">
                <strong>Pedido Id:</strong> {{ str_pad($record->pedido_id, 6, '0', STR_PAD_LEFT) }}<br>
                <strong>Emissão:</strong> {{ now()->format('d/m/Y') }}<br>
                <strong>Previsão de Entrega:</strong> {{ $record->data_entrega_formatada ?? '—' }}<br>
                <strong>Página:</strong> 1
            </div>
        </div>
    </header>

    {{-- Bloco com dados principais --}}
    <section class="section">
        <div class="box">
            <div class="box-col">
                <strong>Cliente:</strong> {{ $record->cliente_nome ?? '—' }}<br>
                <strong>Gerente:</strong> {{ $record->gerente->nome ?? '—' }}
            </div>
            <div class="box-col">
                <strong>Moeda:</strong> {{ $record->moeda->sigla ?? '—' }}<br>
                <strong>Data Entrega:</strong> {{ $record->data_entrega_formatada ?? '—' }}<br>
                <strong>Praça:</strong> {{ $record->pracaCotacao->praca_nome ?? '—' }}
            </div>
        </div>
    </section>

    {{-- Bloco com cotação --}}
    <section class="section">
        <div class="box">
            <div class="box-col">
                <strong>Cultura:</strong> {{ $record->cultura->nome ?? '—' }}<br>
                <strong>Área (ha):</strong> {{ number_format($record->area_cultivo_ha, 2, ',', '.') }}
            </div>
            <div class="box-col">
                <strong>Cotação:</strong> R$ {{ number_format($record->snap_valor_saca_rs, 2, ',', '.') }} /
                US$ {{ number_format($record->snap_valor_saca_us, 2, ',', '.') }}<br>
                <strong>Margem Esperada:</strong> {{ number_format($record->margem_esperada_percentual, 2, ',', '.') }}%
            </div>
        </div>
    </section>

    {{-- Produtos negociados --}}
    <section class="section">
        <h3>Produtos</h3>
        <table>
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

    {{-- Rodapé de assinatura --}}
    <div class="signature">
        <p>Assinatura: ___________________________</p>
        <p>{{ $record->cliente_nome }}</p>
    </div>

    {{-- Rodapé técnico --}}
    <footer>
        CROPER • Relatório gerado em {{ now()->format('d/m/Y H:i') }}
    </footer>

</body>

</html>