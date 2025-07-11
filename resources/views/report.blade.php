{{-- resources/views/report.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Relatório de Negociação #{{ $record->id }}</title>

    {{-- Inline CSS para compatibilidade com Browsershot --}}
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
</head>

<body>
    <header>
        <img src="{{ public_path('images/logo.svg') }}" class="logo" alt="Logo">
        <div class="company">{{ config('app.name', 'Minha Empresa') }}</div>
        <div class="subtitle">Proposta de Venda</div>
    </header>

    <div class="meta">
        <div>
            <strong>Id:</strong><br>
            {{ $record->id }}
        </div>
        <div>
            <strong>Emissão:</strong><br>
            {{ optional($record->emissao)->format('d/m/Y H:i') ?? '–' }}
        </div>
        <div>
            <strong>Página:</strong><br>
            <span class="page"></span>
        </div>
    </div>

    <main>
        {{-- Apenas Dados Básicos (temporariamente) --}}
        @include('reports.partials.dados-basicos')
        <div class="clearfix"></div>
    </main>

    <footer>
        <div class="left">
            {{ config('app.name', 'Minha Empresa') }} © {{ date('Y') }}
        </div>
        <div class="page"></div>
    </footer>
</body>

</html>