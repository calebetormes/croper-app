<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadePeso extends Model
{
    use HasFactory;

    protected $table = 'unidades_peso';
    protected $fillable = ['sigla', 'descricao'];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'tipo_peso_id');
    }
}
