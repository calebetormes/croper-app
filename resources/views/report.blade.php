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
                <strong>RTV:</strong> {{ $record->vendedor->name ?? '—' }}<br>
                <strong>GRV:</strong> {{ $record->gerente->name ?? '—' }}
            </div>
        </div>
    </header>

    {{-- Dados do Cliente --}}
    <section class="section">
        <h3></h3>
        <div style="display: table; width: 100%; font-size: 11px; line-height: 1.4;">
            {{-- Coluna 1: identificação e endereço --}}
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <strong>Razão Social:</strong> {{ $record->cliente }}<br>
                <strong>Endereço:</strong> {{ $record->endereco_cliente }}<br>
                <strong>Cidade:</strong> {{ $record->cidade_cliente }}
            </div>

            {{-- Coluna 2: área, vendedor e gerente --}}
            <div style="display: table-cell; width: 50%; vertical-align: top;">
                <strong>Área (ha):</strong> {{ number_format($record->area_hectares, 2, ',', '.') }}<br>

            </div>
        </div>
    </section>





    {{-- Dados de Praça / Cotação em 5 colunas (usar preco_liquido_saca_valorizado) --}}
    @php
        use Carbon\Carbon;
    @endphp

    <section class="section">
        <h3></h3>
        <div style="display: table; width: 100%; font-size: 11px; line-height: 1.4;">

            {{-- Coluna 1: Cidade --}}
            <div style="display: table-cell; width: 20%; vertical-align: top; padding-right: 5px;">
                <strong>Praça:</strong><br>
                {{ $record->pracaCotacao->cidade ?? '—' }}
            </div>

            {{-- Coluna 3: Data Vencimento --}}
            <div style="display: table-cell; width: 20%; vertical-align: top; padding: 0 5px;">
                <strong>Data Vencimento:</strong><br>
                {{ $record->pracaCotacao->data_vencimento
    ? Carbon::parse($record->pracaCotacao->data_vencimento)->format('d/m/Y')
    : '—' }}
            </div>

            {{-- Coluna 4: Moeda / Cultura --}}
            <div style="display: table-cell; width: 20%; vertical-align: top; padding: 0 5px;">
                <strong>Moeda / Cultura:</strong><br>
                {{ $record->pracaCotacao->moeda->sigla ?? '—' }}
                &nbsp;/&nbsp;
                {{ $record->pracaCotacao->cultura->nome ?? '—' }}
            </div>

            {{-- Coluna 5: Preço da Saca Valorizado --}}
            <div style="display: table-cell; width: 20%; vertical-align: top; padding-left: 5px;">
                <strong>Valor da Saca (R$):</strong><br>
                {{ number_format($record->preco_liquido_saca_valorizado ?? 0, 2, ',', '.') }}
            </div>

        </div>
    </section>

    {{-- Valores Financeiros --}}
    <section class="section">
        <h3></h3>
        <div style="display: table; width: 100%; font-size: 11px; line-height: 1.4;">

            <div style="display: table-row;">

                {{-- Valor total conforme moeda selecionada --}}
                @if($record->moeda->sigla === 'BRL')
                    <div style="display: table-cell; width: 20%; vertical-align: top; padding-right: 5px;">
                        <strong>Valor Total R$:</strong><br>
                        R$ {{ number_format($record->valor_total_pedido_rs_valorizado, 2, ',', '.') }}
                    </div>
                @else
                    <div style="display: table-cell; width: 20%; vertical-align: top; padding-right: 5px;">
                        <strong>Valor Total US$:</strong><br>
                        US$ {{ number_format($record->valor_total_pedido_us_valorizado, 2, ',', '.') }}
                    </div>
                @endif

                {{-- Investimento total em sacas --}}
                <div style="display: table-cell; width: 20%; vertical-align: top; padding: 0 5px;">
                    <strong>Total (sacas):</strong><br>
                    {{ number_format($record->investimento_total_sacas, 2, ',', '.') }}
                </div>

                {{-- Investimento por hectare --}}
                <div style="display: table-cell; width: 20%; vertical-align: top; padding: 0 5px;">
                    <strong>(sacas/ha):</strong><br>
                    {{ number_format($record->investimento_sacas_hectare, 2, ',', '.') }}
                </div>

                {{-- Peso total em kg --}}
                <div style="display: table-cell; width: 20%; vertical-align: top; padding-left: 5px;">
                    <strong>Peso Total (kg):</strong><br>
                    {{ number_format($record->peso_total_kg, 2, ',', '.') }}
                </div>

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