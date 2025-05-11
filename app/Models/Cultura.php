<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cultura extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function pracasCotacao()
    {
        return $this->hasMany(PracaCotacao::class);
    }
}
