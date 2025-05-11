<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Negociacao;
use Illuminate\Support\Facades\DB;

class NegociacaoSeeder extends Seeder
{
    public function run(): void
    {
        // Exemplo de inserção com valores fictícios
        Negociacao::create([
            // Datas
            'data_versao' => now()->subDays(3),
            'data_negocio' => now(),

            // Moeda e pessoas
            'moeda_id' => 1, // Ex: BRL
            'gerente_id' => 2,
            'vendedor_id' => 3,

            // Cliente
            'cliente' => 'Fazenda Exemplo S/A',
            'endereco_cliente' => 'Rodovia BR-101, KM 25',
            'cidade_cliente' => 'Lucas do Rio Verde',

            // Cultura e praça
            'cultura_id' => 1, // Ex: Soja
            'praca_cotacao_id' => 1,
            'pagamento_id' => 1,
            'data_entrega_graos' => now()->addMonths(3),

            // Valores financeiros (simulando fórmulas)
            'valor_total_com_bonus' => 250000.00,
            'area_hectares' => 100.00,
            'investimento_sacas_hectare' => 5.50, // valor fictício
            'investimento_total_sacas' => 550.00, // 100 * 5.5
            'preco_liquido_saca' => 120.75,
            'bonus_cliente_pacote' => 15000.00,
            'valor_total_sem_bonus' => 235000.00,

            // Validações
            'nivel_validacao_id' => 1,
            'status_validacao' => true,
            'status_defensivos' => true,
            'status_especialidades' => false,
            'status_negociacao_id' => 2,

            // Snapshot do preço da praça
            'snap_praca_cotacao_preco' => 118.50,
            'snap_praca_cotacao_preco_fixado' => true,
            'data_atualizacao_snap_preco_praca_cotacao' => now(),

            // Observações
            'observacoes' => 'Negociação de exemplo para testes iniciais.'
        ]);
    }
}
