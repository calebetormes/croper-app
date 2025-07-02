<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegociacaoProduto extends Model
{
    use HasFactory;

    protected $table = 'negociacoes_produtos';
    public $timestamps = false;

    protected $fillable = [
        'negociacao_id',
        'produto_id',
        'volume',
        'indice_valorizacao',
        'snap_produto_preco_rs',
        'snap_produto_preco_us',
        'snap_produto_custo_rs',
        'snap_produto_custo_us',
        'preco_total_produto_negociacao_rs',
        'preco_total_produto_negociacao_us',
        'custo_total_produto_negociacao_rs',    // corrigido
        'custo_total_produto_negociacao_us',    // corrigido
        'margem_faturamento_rs',
        'margem_faturamento_us',
        'preco_produto_valorizado_rs',
        'preco_produto_valorizado_us',
        'data_atualizacao_snap_precos_produtos',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'indice_valorizacao' => 'decimal:2',
        'snap_produto_preco_rs' => 'decimal:2',
        'snap_produto_preco_us' => 'decimal:2',
        'snap_produto_custo_rs' => 'decimal:2',
        'snap_produto_custo_us' => 'decimal:2',
        'preco_total_produto_negociacao_rs' => 'decimal:2',
        'preco_total_produto_negociacao_us' => 'decimal:2',
        'custo_total_produto_negociacao_rs' => 'decimal:2',
        'custo_total_produto_negociacao_us' => 'decimal:2',
        'margem_faturamento_rs' => 'decimal:2',
        'margem_faturamento_us' => 'decimal:2',
        'preco_produto_valorizado_rs' => 'decimal:2',
        'preco_produto_valorizado_us' => 'decimal:2',
        'data_atualizacao_snap_precos_produtos' => 'date',
    ];

    protected $appends = [
        'total_preco_rs',
        'total_preco_us',
        'total_preco_valorizado_rs',
        'total_preco_valorizado_us',
    ];

    public function negociacao(): BelongsTo
    {
        return $this->belongsTo(Negociacao::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function getTotalPrecoRsAttribute(): float
    {
        return $this->snap_produto_preco_rs * $this->volume;
    }

    public function getTotalPrecoUsAttribute(): float
    {
        return $this->snap_produto_preco_us * $this->volume;
    }

    public function getTotalPrecoValorizadoRsAttribute(): float
    {
        return $this->preco_produto_valorizado_rs * $this->volume;
    }

    public function getTotalPrecoValorizadoUsAttribute(): float
    {
        return $this->preco_produto_valorizado_us * $this->volume;
    }
}
