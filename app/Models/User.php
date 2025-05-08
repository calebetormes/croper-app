<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Trait que habilita a criação de factories para testes
    use HasFactory, Notifiable;

    // Atributos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'gerente_id',
        'observacoes',
    ];

    // Atributos ocultos quando o modelo for serializado (ex: para JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Converte atributos para tipos específicos ao acessar/salvar
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Converte para objeto DateTime
            'password' => 'hashed', // Hasheia o password automaticamente
        ];
    }

    // Relacionamento: Um usuário pertence a um papel (role)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relacionamento: Um usuário (vendedor) pertence a qual Gerente
    public function gerente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    // Relacionamento: Um usuário (gerente) pode ter vários vendedores
    public function vendedores(): HasMany
    {
        return $this->hasMany(User::class, 'gerente_id');
    }
}
