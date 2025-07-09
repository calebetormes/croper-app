<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RelatorioNegociacaoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class RelatorioNegociacaoController extends Controller
{
    public function showPdf(int $id)
    {
        // carrega a negociação completa
        $record = (new RelatorioNegociacaoService)->gerar($id);

        // gera o PDF usando a view
        $pdf = Pdf::loadView('reports.relatorio-negociacao-detalhado', compact('record'))
            ->setPaper('a4', 'portrait');

        // retorna o stream de download
        return $pdf->stream("negociacao-{$id}.pdf");
    }
}
