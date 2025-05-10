<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'classe_id',
        'principio_ativo_id',
        'marca_comercial_id',
        'tipo_peso_id',
        'familia_id',
        'apresentacao',
        'dose_sugerida_hectare',
        'preco_real_rs',
        'preco_virtual_rs',
        'preco_real_us',
        'preco_virtual_us',
        'custo_rs',
    ];

    public function classe()
    {
        return $this->belongsTo(ProdutoClasse::class, 'classe_id');
    }

    public function principioAtivo()
    {
        return $this->belongsTo(PrincipioAtivo::class, 'principio_ativo_id');
    }

    public function marcaComercial()
    {
        return $this->belongsTo(MarcaComercial::class, 'marca_comercial_id');
    }

    public function unidadePeso()
    {
        return $this->belongsTo(UnidadePeso::class, 'tipo_peso_id');
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familia_id');
    }
}
