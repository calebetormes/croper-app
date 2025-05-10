<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelValidacao extends Model
{
    public $timestamps = false;
    protected $table = 'niveis_validacao';
    protected $fillable = [
        'nome',
        'ordem_validacao',
    ];
}
