<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'data_pagamento',
        'data_entrega',
    ];
}
