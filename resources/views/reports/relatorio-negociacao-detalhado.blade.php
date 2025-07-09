{{-- resources/views/reports/relatorio-negociacao-detalhado.blade.php --}}
@extends('reports.layouts.report')

@section('content')
    {{-- Título com o número do pedido --}}
    <h1 style="margin-bottom: 16px;">Negociação #{{ $record->pedido_id }}</h1>

    {{-- Seção: Dados Básicos --}}
    @include('reports.partials.dados-basicos', ['record' => $record])

    {{-- Seção: Praça / Cotação --}}
    @include('reports.partials.praca-cotacao', ['record' => $record])

    {{-- Seção: Valores --}}
    @include('reports.partials.valores', ['record' => $record])

    {{-- Seção: Produtos --}}
    @include('reports.partials.produtos', ['record' => $record])

    {{-- Seção: Status --}}
    @include('reports.partials.status', ['record' => $record])
@endsection