<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;  // Adicionada a importação
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    // Trait que habilita a criação de factories para testes
    use HasFactory, Notifiable;
    use LogsActivity;

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

    /**
     * Um usuário (vendedor) pode ter vários gerentes (caso seja many-to-many)
     */
    public function gerentes(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,               // Modelo relacionado
            'gerentes_vendedores',     // Nome da pivot table
            'vendedor_id',             // FK deste model na pivot
            'gerente_id'               // FK do model relacionado na pivot
        );
    }

    /**
     * Um usuário (gerente) pode ter vários vendedores
     */
    public function vendedores(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'gerentes_vendedores',
            'gerente_id',              // FK deste model na pivot
            'vendedor_id'              // FK do model relacionado na pivot
        );
    }

    // Definição das variáveis que o spatie vai monitorar com log
    protected static $recordEvents = [
        'created',
        'updated',
        'deleted',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Users')
            ->logAll()                                          // adicionado: monitora todos os atributos
            ->logOnlyDirty()                                    // já existente: só registra atributos que mudaram
            ->dontSubmitEmptyLogs();                            // já existente: ignora quando não há mudanças
    }

    protected static function booted()
    {
        parent::booted();

        // Quando der attach em “vendedores”
        static::pivotAttached(function ($user, string $relationName, array $pivotIds) {
            if ($relationName === 'vendedores') {
                activity()
                    ->performedOn($user)
                    ->causedBy(auth()->user())
                    ->withProperties(['added_vendedores' => $pivotIds])
                    ->log('Vendedores vinculados');
            }
        });

        // Quando der detach em “vendedores”
        static::pivotDetached(function ($user, string $relationName, array $pivotIds) {
            if ($relationName === 'vendedores') {
                activity()
                    ->performedOn($user)
                    ->causedBy(auth()->user())
                    ->withProperties(['removed_vendedores' => $pivotIds])
                    ->log('Vendedores desvinculados');
            }
        });
    }
}
