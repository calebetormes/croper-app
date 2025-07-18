<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfReportService
{
    public static function generate(string $view, array $data): string
    {
        return Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->output();
    }
}
