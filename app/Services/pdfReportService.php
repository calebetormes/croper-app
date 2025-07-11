<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;

class PdfReportService
{
    /**
     * Gera um PDF a partir de HTML usando headless Chrome (Browsershot).
     *
     * @param  string  $html
     * @return string  Conteúdo binário do PDF
     */
    public static function generate(string $html): string
    {
        return Browsershot::html($html)
            ->showBackground()          // renderiza fundo e cores
            ->format('A4')             // tamanho A4
            ->margins(15, 15, 20, 15)   // margens em mm: left, right, top, bottom
            ->pdf();                   // retorna os bytes do PDF
    }
}
