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
        Schema::create('pracas_cotacoes', function (Blueprint $table) {
            $table->id();
            $table->string('cidade');
            $table->date('data_inclusao');
            $table->date('data_vencimento');
            $table->decimal('praca_cotacao_preco', 12, 2)->nullable();
            //$table->decimal('fator_valorizacao', 12, 2)->nullable();
            $table->unsignedBigInteger('cultura_id');
            $table->unsignedBigInteger('moeda_id');
            $table->foreign('cultura_id')->references('id')->on('culturas')->onDelete('cascade');
            $table->foreign('moeda_id')->references('id')->on('moedas')->onDelete('cascade');

            // índice único composto: cidade + data_vencimento + moeda_id + cultura_id
            $table->unique(
                ['cidade', 'data_vencimento', 'cultura_id', 'moeda_id',],
                'pracas_cotacoes_unico'
            );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pracas_cotacoes');
    }
};
