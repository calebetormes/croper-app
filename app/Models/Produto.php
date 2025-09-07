<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    // A tabela já bate com a convenção ("produtos"), então não precisa declarar $table.
    public $timestamps = false;

    protected $fillable = [
        'classe_id',
        'principio_ativo_id',
        'marca_comercial_id',
        'tipo_peso_id',
        'familia_id',
        'apresentacao',
        'dose_sugerida_hectare',
        'preco_rs',
        'preco_us',
        'custo_rs',
        'custo_us',
        'fator_multiplicador',
        'ativo',
        'indice_valorizacao_produto',
    ];

    /**
     * Defaults em memória (espelha os defaults da migration).
     */
    protected $attributes = [
        'fator_multiplicador' => 1.0,
        'ativo' => true,
    ];

    /**
     * Casts coerentes com a migration (12,2 nos decimais).
     */
    protected $casts = [
        'preco_rs' => 'decimal:2',
        'preco_us' => 'decimal:2',
        'custo_rs' => 'decimal:2',
        'custo_us' => 'decimal:2',
        'fator_multiplicador' => 'decimal:2',
        'indice_valorizacao_produto' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    /**
     * Se for útil em APIs/JSON.
     */
    protected $appends = ['nome_composto'];

    // ----------------- Relacionamentos -----------------

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

    // ----------------- Accessors / Scopes -----------------

    /**
     * Rótulo composto para Filament e exibição geral.
     */
    public function getNomeCompostoAttribute(): string
    {
        $parts = [];

        if ($this->relationLoaded('classe') ? $this->classe : $this->classe()->exists()) {
            $parts[] = $this->classe->nome;
        }

        if ($this->relationLoaded('principioAtivo') ? $this->principioAtivo : $this->principioAtivo()->exists()) {
            $parts[] = $this->principioAtivo->nome;
        }

        if ($this->relationLoaded('marcaComercial') ? $this->marcaComercial : $this->marcaComercial()->exists()) {
            $parts[] = $this->marcaComercial->nome;
        }

        $parts[] = (string) $this->apresentacao;

        return implode(' – ', array_filter($parts));
    }

    /**
     * Escopo prático para consultar apenas produtos ativos.
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
