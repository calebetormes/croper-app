<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracaCotacao extends Model
{
    use HasFactory;

    protected $table = 'pracas_cotacoes';

    protected $fillable = [
        'cidade',
        'data_vencimento',
        'praca_cotacao_preco',
        'moeda_id',
        'cultura_id',
        'fator_valorizacao'
    ];

    public function cultura()
    {
        return $this->belongsTo(Cultura::class);
    }

    public function moeda()
    {
        return $this->belongsTo(Moeda::class);
    }
}
