<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\RelatorioNegociacaoController;

Route::get('/', function () {
    return redirect('/admin');
});


Route::get('/negociacoes/{id}/pdf', [ReportController::class, 'gerarPdf'])
    ->name('negociacoes.pdf');