<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegociacaoProduto extends Model
{
    // protected $guarded = [];

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
        'data_atualizacao_snap_precos_produtos' => 'datetime',
    ];

    public function negociacao(): BelongsTo
    {
        return $this->belongsTo(Negociacao::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
