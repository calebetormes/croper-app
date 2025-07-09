{{-- resources/views/reports/layouts/report.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20mm 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header,
        footer {
            width: 100%;
            position: fixed;
            text-align: center;
        }

        header {
            top: 0;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        footer {
            bottom: 0;
            padding: 5px 0;
            font-size: 10px;
            border-top: 1px solid #ccc;
        }

        .page-break {
            page-break-after: always;
        }

        main {
            margin: 60px 0 40px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .info-grid .item strong {
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    <header>
        {{-- Se quiser, coloque aqui sua logo: --}}
        {{-- <img src="{{ public_path('images/logo.png') }}" style="height:40px;"> --}}
        <h2>Relatório de Negociação</h2>
    </header>

    <footer>
        Página <span class="pageNumber"></span> de <span class="totalPages"></span>
    </footer>

    <main>
        @yield('content')
    </main>

</body>

</html>