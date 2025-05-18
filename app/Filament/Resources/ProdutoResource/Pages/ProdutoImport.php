<?php

namespace App\Filament\Resources\ProdutoResource\Pages;

use App\Models\Produto;
use App\Models\ProdutoClasse;
use App\Models\PrincipioAtivo;
use App\Models\MarcaComercial;
use App\Models\UnidadePeso;
use App\Models\Familia;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdutoImport implements ToModel, WithHeadingRow
{
    /**
     * Retorna uma instância de Produto para cada linha do CSV
     *
     * @param array $row
     * @return \App\Models\Produto|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $classeId    = ProdutoClasse::where('nome', $row['classe'])->value('id') ?? 0;
            $principioId = PrincipioAtivo::where('nome', $row['principio ativo'])->value('id') ?? 0;
            $marcaId     = MarcaComercial::where('nome', $row['marca comercial'])->value('id') ?? 0;
            $pesoId      = UnidadePeso::where('sigla', $row['tipo de peso'])->value('id') ?? 0;
            $familiaId   = Familia::where('nome', $row['familia'])->value('id') ?? 0;

            return new Produto([
                'classe_id'             => $classeId,
                'principio_ativo_id'    => $principioId,
                'marca_comercial_id'    => $marcaId,
                'tipo_peso_id'          => $pesoId,
                'familia_id'            => $familiaId,
                'apresentacao'          => $row['apresentação'] ?? '',
                'dose_sugerida_hectare' => $row['dose sugerida hectare'] ?? '',
                'preco_real_rs'         => $row['preço real r$'] ?? 0.00,
                'preco_virtual_rs'      => $row['preço virtual r$'] ?? 0.00,
                'preco_real_us'         => $row['preço real u$'] ?? 0.00,
                'preco_virtual_us'      => $row['preço virtual u$'] ?? 0.00,
                'custo_rs'              => $row['custo'] ?? 0.00,
            ]);
        });
    }
}
