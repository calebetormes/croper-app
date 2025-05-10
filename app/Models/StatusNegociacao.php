<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusNegociacao extends Model
{
    public $timestamps = false;
    protected $table = 'status_negociacoes';

    protected $fillable = [
        'nome',
        'descricao',
    ];
}
