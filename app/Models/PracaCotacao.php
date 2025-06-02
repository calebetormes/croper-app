<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;


class PracaCotacao extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::creating(function ($record) {
            $exists = self::where('cidade', $record->cidade)
                ->where('data_vencimento', $record->data_vencimento)
                ->where('moeda_id', $record->moeda_id)
                ->where('cultura_id', $record->cultura_id)
                ->exists();

            if ($exists) {
                // Ao lançar uma Exception comum, o Filament exibirá o texto como toast de erro.
                throw new \Exception('Já existe uma cotação para esta combinação de cidade, data, moeda e cultura.');
            }
        });

        static::updating(function ($record) {
            $exists = self::where('cidade', $record->cidade)
                ->where('data_vencimento', $record->data_vencimento)
                ->where('moeda_id', $record->moeda_id)
                ->where('cultura_id', $record->cultura_id)
                ->where('id', '<>', $record->id)
                ->exists();

            if ($exists) {
                throw new \Exception('Já existe uma cotação para esta combinação de cidade, data, moeda e cultura.');
            }
        });
    }

    protected $table = 'pracas_cotacoes';

    protected $fillable = [
        'cidade',
        'data_inclusao',
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
