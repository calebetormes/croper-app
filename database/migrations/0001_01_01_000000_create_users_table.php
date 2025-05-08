<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela de roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Vendedor, Gerente, etc.
            $table->timestamps();
        });

        // Tabela de users
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Chave estrangeira para roles
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();

            // Chave estrangeira recursiva para outro usuário
            $table->foreignId('gerente_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('observacoes')->nullable();
        });

        // TABELAS TÉCNICAS
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerente_vendedor');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
