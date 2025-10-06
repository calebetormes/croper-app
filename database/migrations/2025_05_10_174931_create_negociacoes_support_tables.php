<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->date('data_pagamento')->nullable();
            $table->date('data_entrega')->nullable();
            $table->boolean('ativo')->default(true);
        });

        Schema::create('culturas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
        });

        Schema::create('moedas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('sigla');
        });

        Schema::create('niveis_validacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Ex: Gerente Comercial
            $table->unsignedInteger('ordem_validacao');
        });

        Schema::create('status_negociacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Ex: Rascunho, Em análise, etc.
            $table->text('descricao')->nullable();
            $table->string('cor')->nullable(); // Ex: #4CAF50 ou bg-green-500
            $table->integer('ordem')->default(0);
            $table->string('icone')->nullable(); // Ex: check-circle
            $table->boolean('finaliza_negociacao')->default(false);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_negociacoes');
        Schema::dropIfExists('niveis_validacao');
        Schema::dropIfExists('moedas');
        Schema::dropIfExists('culturas');
        Schema::dropIfExists('pagamentos');
    }
};
