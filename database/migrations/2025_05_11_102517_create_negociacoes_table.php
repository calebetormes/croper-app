<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negociacoes', function (Blueprint $table) {
            $table->id();

            // Datas principais
            $table->date('data_versao')->nullable();
            $table->date('data_negocio');

            // Moeda e pessoas
            $table->foreignId('moeda_id')->constrained('moedas');
            $table->foreignId('gerente_id')->constrained('users');
            $table->foreignId('vendedor_id')->constrained('users');

            // Dados do cliente
            $table->string('cliente');
            $table->string('endereco_cliente')->nullable();
            $table->string('cidade_cliente')->nullable();

            // Cultura e praça
            $table->foreignId('cultura_id')->constrained('culturas');
            $table->foreignId('praca_cotacao_id')->constrained('pracas_cotacoes');
            $table->foreignId('pagamento_id')->constrained('pagamentos');
            $table->date('data_entrega_graos');

            // Valores financeiros
            $table->decimal('valor_total_com_bonus', 12, 2);
            $table->decimal('area_hectares', 12, 2)->nullable();
            $table->decimal('investimento_sacas_hectare', 12, 2)->nullable();
            $table->decimal('investimento_total_sacas', 12, 2)->nullable();
            $table->decimal('preco_liquido_saca', 12, 2)->nullable();
            $table->decimal('bonus_cliente_pacote', 12, 2)->nullable();
            $table->decimal('valor_total_sem_bonus', 12, 2)->nullable();

            // Validações
            $table->foreignId('nivel_validacao_id')->constrained('niveis_validacao');
            $table->boolean('status_validacao')->default(false);
            $table->unsignedBigInteger('status_defensivos')->default(false);
            $table->unsignedBigInteger('status_especialidades')->default(false);
            $table->foreignId('status_negociacao_id')->constrained('status_negociacoes')->nullable();

            // Snapshots de preço da praça
            $table->decimal('snap_praca_cotacao_preco', 12, 2)->nullable();
            $table->boolean('snap_praca_cotacao_preco_fixado')->default(false);
            $table->date('data_atualizacao_snap_preco_praca_cotacao')->nullable();

            // Observações
            $table->text('observacoes')->nullable();

            // Timestamps padrão (se desejar)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negociacoes');
    }
};
