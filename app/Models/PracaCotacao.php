<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PracaCotacao extends Model
{
    use HasFactory;

    protected $table = 'pracas_cotacoes';

    protected $fillable = [
        'cidade',
        'preco',
        'data_vencimento',
        'praca_cotacao_preco',
        'moeda_id',
        'cultura_id',
    ];

    public function cultura()
    {
        return $this->belongsTo(Cultura::class);
    }
}
