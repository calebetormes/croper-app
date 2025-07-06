<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negociacoes_produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('negociacao_id');
            $table->unsignedInteger('produto_id');
            $table->decimal('volume', 12, 2);
            $table->decimal('indice_valorizacao', 12, 2);

            // Campos snapshot para os preços
            $table->decimal('snap_produto_preco_rs', 12, 2)->nullable();
            $table->decimal('snap_produto_preco_us', 12, 2)->nullable();
            $table->decimal('snap_produto_custo_rs', 12, 2)->nullable();
            $table->decimal('snap_produto_custo_us', 12, 2)->nullable();
            //
            $table->decimal('preco_total_produto_negociacao_rs', 12, 2)->nullable();
            $table->decimal('preco_total_produto_negociacao_us', 12, 2)->nullable();
            $table->decimal('custo_total_produto_negociacao_rs', 12, 2)->nullable();
            $table->decimal('custo_total_produto_negociacao_us', 12, 2)->nullable();
            //
            $table->decimal('margem_faturamento_rs', 12, 2)->nullable();
            $table->decimal('margem_faturamento_us', 12, 2)->nullable();
            $table->decimal('margem_percentual_rs', 12, 2)->nullable(); // (100/1 - (snap_produto_custo_rs/snap_produto_preco_rs)) * 100
            $table->decimal('margem_percentual_us', 12, 2)->nullable(); // (100/1 - (snap_produto_custo_us/snap_produto_pre
            //
            $table->decimal('preco_produto_valorizado_rs', 12, 2)->nullable();
            $table->decimal('preco_produto_valorizado_us', 12, 2)->nullable();

            $table->date('data_atualizacao_snap_precos_produtos');

            // Foreign keys
            $table->foreign('negociacao_id')
                ->references('id')->on('negociacoes')
                ->onDelete('cascade');

            $table->foreign('produto_id')
                ->references('id')->on('produtos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negociacoes_produtos');
    }
};
