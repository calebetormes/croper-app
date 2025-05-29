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
        // Tabelas de lookup
        Schema::create('produtos_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
        });

        Schema::create('principios_ativos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
        });

        Schema::create('marcas_comerciais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
        });

        Schema::create('unidades_peso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sigla')->unique();   // ex: mg, g, kg, ton
            $table->string('descricao');         // ex: miligrama, grama, quilo, tonelada
        });

        Schema::create('familias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
        });

        // Tabela principal de produtos
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('classe_id');
            $table->unsignedInteger('principio_ativo_id');
            $table->unsignedInteger('marca_comercial_id');
            $table->unsignedInteger('tipo_peso_id');
            $table->unsignedInteger('familia_id');

            $table->string('apresentacao');
            $table->string('dose_sugerida_hectare');

            $table->decimal('preco_real_rs', 12, 2);
            $table->decimal('preco_virtual_rs', 12, 2);
            $table->decimal('preco_real_us', 12, 2);
            $table->decimal('preco_virtual_us', 12, 2);
            $table->decimal('custo_rs', 12, 2);
            $table->decimal('custo_us', 12, 2);

            // chaves estrangeiras
            $table->foreign('classe_id')
                ->references('id')->on('produtos_classes')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('principio_ativo_id')
                ->references('id')->on('principios_ativos')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('marca_comercial_id')
                ->references('id')->on('marcas_comerciais')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('tipo_peso_id')
                ->references('id')->on('unidades_peso')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('familia_id')
                ->references('id')->on('familias')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('familias');
        Schema::dropIfExists('unidades_peso');
        Schema::dropIfExists('marcas_comerciais');
        Schema::dropIfExists('principios_ativos');
        Schema::dropIfExists('produtos_classes');
    }
};
