<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            // Adiciona o campo "ativo" caso não exista
            if (!Schema::hasColumn('pagamentos', 'ativo')) {
                $table->boolean('ativo')->default(true)->after('data_entrega');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};

