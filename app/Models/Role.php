<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // Campos permitidos para preenchimento em massa
    protected $fillable = ['name'];

    // Relacionamento: Um papel (role) pode ter vários usuários
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
