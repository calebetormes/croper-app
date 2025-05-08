<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GerenteVendedor extends Model
{
    // Nome da tabela no banco (não segue o padrão pluralizado do Laravel)
    protected $table = 'gerente_vendedor';

    // A tabela não tem campos created_at e updated_at
    public $timestamps = false;

    // Não há uma chave primária convencional (como id)
    protected $primaryKey = null;

    public $incrementing = false;

    // Campos permitidos para preenchimento em massa
    protected $fillable = [
        'gerente_id',
        'vendedor_id',
    ];

    // Relacionamento: essa linha pertence a um gerente (usuário)
    public function gerente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    // Relacionamento: essa linha pertence a um vendedor (usuário)
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }
}
