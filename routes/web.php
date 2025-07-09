<?php

use Illuminate\Support\Facades\Route;
use App\Models\Negociacao;
use Filament\Infolists\Infolist;
use App\Filament\Resources\NegociacaoResource\Infolist\Sections\{
    DadosBasicosInfolist,
    PracaCotacaoInfolist,
    ProdutosInfolist,
    StatusInfolist,
    ValoresInfolist,
};

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/teste-pdf', function () {
    $pdf = PDF::loadHTML('<h1>✔️ DomPDF Funcionando!</h1><p>Seu pacote está pronto.</p>');
    return $pdf->stream('teste.pdf');
});

Route::get('/teste-template', function () {
    // cria um objeto “record” com o campo pedido_id para injetar na view
    $record = (object) [
        'pedido_id' => 12345,
    ];

    return view('reports.relatorio-negociacao', compact('record'));
});

// Rota de teste do relatório
Route::get('/teste-relatorio', function () {
    // Busca o primeiro registro sem tentar carregar 'cliente' como relação
    $record = Negociacao::first();

    $infolist = Infolist::make()
        ->record($record)
        ->sections([
            DadosBasicosInfolist::make(),
            PracaCotacaoInfolist::make(),
            ProdutosInfolist::make(),
            StatusInfolist::make(),
            ValoresInfolist::make(),
        ]);

    return view('reports.relatorio-negociacao', compact('record', 'infolist'));
});