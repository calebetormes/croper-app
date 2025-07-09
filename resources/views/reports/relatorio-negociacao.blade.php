{{-- resources/views/reports/relatorio-negociacao.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h1,
        {
            {
            -- ...cabeçalho e <h1>já definidos --
        }
        }

        {!! $infolist->render() !!}
        h2 {
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Relatório de Negociação – Pedido #{{ $record->id }}</h1>

    {{-- Renderiza todas as seções do Infolist --}}
    {!! $infolist->render() !!}
</body>

</html>