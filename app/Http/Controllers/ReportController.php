<?php

namespace App\Http\Controllers;

use App\Models\Negociacao;
use App\Services\PdfReportService;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function gerarPdf(int $id): Response
    {
        // Carrega os atributos simples automaticamente,
        // e faz eager load apenas das relações definidas no model
        $record = Negociacao::with([
            'moeda',
            'gerente',
            'vendedor',
            'cultura',
            'pracaCotacao',
            'pagamento',
            'nivelValidacao',
            'statusNegociacao',
            'negociacaoProdutos.produto',
        ])->findOrFail($id);

        $pdfContent = PdfReportService::generate('report', compact('record'));

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="negociacao-' . $record->pedido_id . '.pdf"',
        ]);
    }
}
