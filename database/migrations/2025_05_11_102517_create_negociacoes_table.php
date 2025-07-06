<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negociacoes', function (Blueprint $table) {
            $table->id();
            $table->string('pedido_id', 6)->nullable()->unique();

            // Datas principais
            $table->date('data_versao')->nullable();
            $table->date('data_negocio')->nullable();
            $table->foreignId('moeda_id')->constrained('moedas');

            // pessoas
            $table->foreignId('gerente_id')->constrained('users');
            $table->foreignId('vendedor_id')->constrained('users');
            $table->string('cliente')->nullable();
            $table->string('endereco_cliente')->nullable();
            $table->string('cidade_cliente')->nullable();

            // Cultura e praça
            $table->foreignId('cultura_id')->constrained('culturas');
            $table->foreignId('praca_cotacao_id')->constrained('pracas_cotacoes');
            $table->foreignId('pagamento_id')->constrained('pagamentos');
            $table->date('data_entrega_graos')->nullable();

            // Snapshots de preço da praça
            //$table->decimal('snap_praca_cotacao_fator_valorizacao', 12, 2)->nullable();
            $table->decimal('snap_praca_cotacao_preco', 12, 2)->nullable();
            $table->date('data_atualizacao_snap_preco_praca_cotacao')->nullable();

            // Valores financeiros
            $table->decimal('area_hectares', 12, 2)->nullable();
            $table->decimal('valor_total_pedido_rs', 12, 2)->nullable();
            $table->decimal('valor_total_pedido_us', 12, 2)->nullable();
            $table->decimal('valor_total_pedido_rs_valorizado', 12, 2)->nullable();
            $table->decimal('valor_total_pedido_us_valorizado', 12, 2)->nullable();
            $table->decimal('margem_faturamento_total_us', 12, 2)->nullable();
            $table->decimal('margem_faturamento_total_rs', 12, 2)->nullable();
            $table->decimal('margem_percentual_total_us', 12, 2)->nullable(); // (100/1 - (snap_produto_custo_us/snap_produto_preco_us)) * 100
            $table->decimal('margem_percentual_total_rs', 12, 2)->nullable(); // (100/1 - (snap_produto_custo_rs/snap_produto_pre

            //Deve ser feito uma verificação para ver se o pedido é em RS ou US
            //se for em RS, sera valor_total_pedido_rs dividido pelo preco_rs do produto
            //se for em US, sera valor_total_pedido_us dividido pelo preco_us do produto
            $table->decimal('investimento_total_sacas', 12, 2)->nullable();

            $table->decimal('investimento_sacas_hectare', 12, 2)->nullable();

            $table->decimal('indice_valorizacao_saca', 12, 2)->nullable();
            $table->decimal('preco_liquido_saca', 12, 2)->nullable();
            $table->decimal('preco_liquido_saca_valorizado', 12, 2)->nullable();


            $table->decimal('bonus_cliente_pacote', 12, 2)->nullable();
            $table->decimal('peso_saca', 12, 2)->nullable();
            $table->decimal('peso_total_kg', 12, 2)->nullable();

            // Validações
            $table->foreignId('nivel_validacao_id')->constrained('niveis_validacao')->nullable();
            $table->integer('status_defensivos')->nullable();
            $table->integer('status_especialidades')->nullable();
            $table->foreignId('status_negociacao_id')->default(1)->constrained('status_negociacoes')->nullable();


            $table->text('observacoes')->nullable();
            $table->decimal('cotacao_moeda_usd_brl', 12, 2)->nullable();

            // Timestamps padrão (se desejar)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negociacoes');
    }
};
