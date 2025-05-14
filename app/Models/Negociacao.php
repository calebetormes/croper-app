<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // ← importe o HasMany correto

// se ainda não estiver

class Negociacao extends Model
{
    use HasFactory;

    protected $table = 'negociacoes';

    /**
     * Campos que podem ser preenchidos em massa (mass assignment)
     * Organizados por blocos lógicos conforme a migration.
     */
    protected $fillable = [

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
        'valor_total_com_bonus',
        'area_hectares',
        'investimento_sacas_hectare',
        'investimento_total_sacas',
        'preco_liquido_saca',
        'bonus_cliente_pacote',
        'valor_total_sem_bonus',

        // Validações
        'nivel_validacao_id',
        'status_validacao',
        'status_defensivos',
        'status_especialidades',
        'status_negociacao_id',

        // Snapshots de preço da praça
        'snap_praca_cotacao_preco',
        'snap_praca_cotacao_preco_fixado',
        'data_atualizacao_snap_preco_praca_cotacao',

        // Observações
        'observacoes',
    ];

    // Relação com a moeda utilizada na negociação
    public function moeda()
    {
        return $this->belongsTo(Moeda::class);
    }

    // Relação com o gerente da negociação
    public function gerente()
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    // Relação com o vendedor responsável
    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    // Relação com a cultura agrícola da negociação
    public function cultura()
    {
        return $this->belongsTo(Cultura::class);
    }

    // Relação com a praça de cotação associada
    public function pracaCotacao()
    {
        return $this->belongsTo(PracaCotacao::class, 'praca_cotacao_id');
    }

    // Relação com a condição de pagamento
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }

    // Relação com o nível de validação do processo
    public function nivelValidacao()
    {
        return $this->belongsTo(NivelValidacao::class, 'nivel_validacao_id');
    }

    // Relação com o status atual da negociação
    public function statusNegociacao()
    {
        return $this->belongsTo(StatusNegociacao::class, 'status_negociacao_id');
    }

    /**
     * Relação com o pivot NegociacaoProduto
     */
    public function negociacaoProdutos(): HasMany
    {
        return $this->hasMany(NegociacaoProduto::class, 'negociacao_id');
    }
}
