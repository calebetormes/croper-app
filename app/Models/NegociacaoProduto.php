<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegociacaoProduto extends Model
{
    protected $table = 'negociacoes_produtos';

    protected $primaryKey = 'id';

    public $timestamps = false;

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
        'volume' => 'decimal:2',
        'potencial_produto' => 'decimal:2',
        'dose_hectare' => 'decimal:2',
        'snap_produto_preco_real_rs' => 'decimal:2',
        'snap_produto_preco_real_us' => 'decimal:2',
        'snap_produto_preco_virtual_rs' => 'decimal:2',
        'snap_produto_preco_virtual_us' => 'decimal:2',
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
