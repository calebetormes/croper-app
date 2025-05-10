<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoClasse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'produtos_classes';

    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'classe_id');
    }
}
