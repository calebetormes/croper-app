<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaComercial extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'marcas_comerciais';

    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'marca_comercial_id');
    }
}
