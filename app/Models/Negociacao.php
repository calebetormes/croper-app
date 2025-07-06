<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Negociacao extends Model
{
    use HasFactory;

    protected $table = 'negociacoes';

    protected $fillable = [
        'pedido_id',

        // Datas principais
        'data_versao',
        'data_negocio',

        // Moeda e pessoas
        'moeda_id',
        'gerente_id',
        'vendedor_id',

        // Dados do cliente
        'cliente',
        'endereco_cliente',
        'cidade_cliente',

        // Cultura, praça e pagamento
        'cultura_id',
        'praca_cotacao_id',
        'pagamento_id',
        'data_entrega_graos',

        // Snapshots de preço da praça
        'snap_praca_cotacao_preco',
        'data_atualizacao_snap_preco_praca_cotacao',

        // Valores financeiros
        'area_hectares',
        'valor_total_pedido_rs',
        'valor_total_pedido_us',
        'valor_total_pedido_rs_valorizado',
        'valor_total_pedido_us_valorizado',
        'investimento_total_sacas',
        'investimento_sacas_hectare',
        'indice_valorizacao_saca',
        'preco_liquido_saca',
        'preco_liquido_saca_valorizado',
        'bonus_cliente_pacote',
        'peso_saca',
        'peso_total_kg',
        'margem_faturamento_total_us',
        'margem_faturamento_total_rs',
        'margem_percentual_total_us', // (100/1 - (snap_produto_custo_us/snap_produto_preco_us)) * 100
        'margem_percentual_total_rs', // (100/1 -

        // Validações e status
        'nivel_validacao_id',
        'status_defensivos',
        'status_especialidades',
        'status_negociacao_id',

        // Observações e câmbio
        'observacoes',
        'cotacao_moeda_usd_brl',
    ];

    // Casts para datas, booleanos e valores decimais
    protected $casts = [
        'data_versao' => 'date',
        'data_negocio' => 'date',
        'data_atualizacao_snap_preco_praca_cotacao' => 'date',
        'status_validacao' => 'boolean',
        'valor_total_pedido_rs' => 'decimal:2',
        'valor_total_pedido_us' => 'decimal:2',
        'valor_total_pedido_rs_valorizado' => 'decimal:2',
        'valor_total_pedido_us_valorizado' => 'decimal:2',
        'area_hectares' => 'decimal:2',
        'investimento_total_sacas' => 'decimal:2',
        'investimento_sacas_hectare' => 'decimal:2',
        'indice_valorizacao_saca' => 'decimal:2',
        'preco_liquido_saca' => 'decimal:2',
        'preco_liquido_saca_valorizado' => 'decimal:2',
        'bonus_cliente_pacote' => 'decimal:2',
        'peso_total_kg' => 'decimal:2',
        'cotacao_moeda_usd_brl' => 'decimal:2',
        'data_entrega_graos' => 'date',
        'snap_praca_cotacao_preco' => 'decimal:2',
        'status_defensivos' => 'integer',
        'status_especialidades' => 'integer',
        'peso_saca' => 'decimal:2',
        'margem_faturamento_total_us' => 'decimal:2',
        'margem_faturamento_total_rs' => 'decimal:2',
        'margem_percentual_total_us' => 'decimal:2', // (100/1 - (snap_produto_custo_us/snap_produto_preco_us)) * 100
        'margem_percentual_total_rs' => 'decimal:2', // (100/1 - (snap_produto_custo_rs/snap_produto_preco_rs)) * 100

    ];

    public $timestamps = true;

    protected static function booted()
    {
        static::created(function (Negociacao $negociacao) {
            $negociacao->updateQuietly([
                'pedido_id' => str_pad($negociacao->id, 6, '0', STR_PAD_LEFT),
            ]);
        });
    }


    // Relações
    public function moeda()
    {
        return $this->belongsTo(Moeda::class);
    }

    public function gerente()
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function cultura()
    {
        return $this->belongsTo(Cultura::class);
    }

    public function pracaCotacao()
    {
        return $this->belongsTo(PracaCotacao::class, 'praca_cotacao_id');
    }

    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

    public function nivelValidacao()
    {
        return $this->belongsTo(NivelValidacao::class, 'nivel_validacao_id');
    }

    public function statusNegociacao()
    {
        return $this->belongsTo(StatusNegociacao::class, 'status_negociacao_id');
    }

    public function negociacaoProdutos(): HasMany
    {
        return $this->hasMany(NegociacaoProduto::class, 'negociacao_id');
    }
}
