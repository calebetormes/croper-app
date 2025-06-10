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
            $table->decimal('snap_produto_preco_rs', 12, 2);
            $table->decimal('snap_produto_preco_us', 12, 2);

            $table->decimal('preco_produto_valorizado_rs', 12, 2);
            $table->decimal('preco_produto_valorizado_us', 12, 2);

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
