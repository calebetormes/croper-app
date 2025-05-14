<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegociacaoProduto extends Model
{
    use HasFactory;

    protected $table = 'negociacoes_produtos';

    public $timestamps = false; // <-- Adicione essa linha aqui

    protected $fillable = [
        'negociacao_id',
        'produto_id',
        'volume',
        'potencial_produto',
        'dose_hectare',
        'snap_produto_preco_real_rs',
        'snap_produto_preco_real_us',
        'snap_produto_preco_virtual_rs',
        'snap_produto_preco_virtual_us',
        'snap_precos_fixados',
        'data_atualizacao_snap_precos_produtos',
    ];

    protected $casts = [
        'snap_precos_fixados' => 'boolean',
        'data_atualizacao_snap_precos_produtos' => 'date',
    ];

    public function negociacao(): BelongsTo
    {
        return $this->belongsTo(Negociacao::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
