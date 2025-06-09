<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Negociacao extends Model
{
    use HasFactory;

    protected $table = 'negociacoes';

    protected $casts = [
        //'negociacaoProdutos' => 'array',
    ];


    protected static function booted()
    {
        static::created(function (Negociacao $negociacao) {
            $negociacao->updateQuietly([
                'pedido_id' => str_pad($negociacao->id, 6, '0', STR_PAD_LEFT), // ← padding para 6 dígitos
            ]);
        });
    }

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

        // Cultura e praça
        'cultura_id',
        'praca_cotacao_id',
        'pagamento_id',
        'data_entrega_graos',

        // Valores financeiros
        'valor_total_com_bonus_rs',
        'valor_total_com_bonus_us',
        'valor_total_sem_bonus_rs',
        'valor_total_sem_bonus_us',
        'valor_total_com_bonus_sacas',
        'valor_total_sem_bonus_sacas',
        'peso_total_kg',
        'area_hectares',
        'investimento_sacas_hectare',
        'investimento_total_sacas',
        'preco_liquido_saca',
        'bonus_cliente_pacote',

        // Validações
        'nivel_validacao_id',
        'status_validacao',
        'status_defensivos',
        'status_especialidades',
        'status_negociacao_id',

        // Snapshots de preço da praça
        'snap_praca_cotacao_preco',
        'snap_praca_cotacao_fator_valorizacao',
        'snap_praca_cotacao_preco_fixado',
        'data_atualizacao_snap_preco_praca_cotacao',


        // Observações
        'observacoes',
    ];


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
