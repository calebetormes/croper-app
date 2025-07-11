<?php

namespace App\Http\Controllers;

use App\Models\Negociacao;
use App\Services\PdfReportService;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Gera o PDF da negociação usando PdfReportService (Spatie Browsershot).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gerarPdf(int $id): Response
    {
        // Busca a negociação com os relacionamentos necessários
        $record = Negociacao::with([
            'moeda',
            'gerente',
            'vendedor',
            'cultura',
            'pracaCotacao',
            'pagamento',
            'negociacaoProdutos.produto',
        ])->findOrFail($id);

        // Renderiza a view Blade para HTML
        $html = view('report', compact('record'))->render();

        // Gera o PDF (binário) a partir do HTML
        $pdfContent = PdfReportService::generate($html);

        // Retorna o PDF direto na resposta HTTP
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf');
    }
}
