<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nome'];

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'familia_id');
    }
}
