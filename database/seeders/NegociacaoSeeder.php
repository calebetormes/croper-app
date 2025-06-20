<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Negociacao;

class NegociacaoSeeder extends Seeder
{
    public function run(): void
    {
        Negociacao::create([
            // Identificador do pedido (6 caracteres)
            'pedido_id' => 'PED001',

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

            // Cultura, praça de cotação e forma de pagamento
            'cultura_id' => 1, // Ex: Soja
            'praca_cotacao_id' => 1,
            'pagamento_id' => 1,
            'data_entrega_graos' => now()->addDays(30),

            // Snapshot do preço da praça
            'snap_praca_cotacao_preco' => 118.50,
            'data_atualizacao_snap_preco_praca_cotacao' => now(),

            // Valores financeiros
            'area_hectares' => 100.00,
            'valor_total_pedido_rs' => 235000.00,
            'valor_total_pedido_us' => 47000.00,
            'valor_total_pedido_rs_valorizado' => 240000.00,
            'valor_total_pedido_us_valorizado' => 48000.00,
            'investimento_total_sacas' => 550.00,
            'investimento_sacas_hectare' => 5.50,
            'indice_valorizacao_saca' => 0.50,
            'preco_liquido_saca' => 120.75,
            'preco_liquido_saca_valorizado' => 122.00,
            'bonus_cliente_pacote' => 15000.00,
            'peso_total_kg' => 100000.00,

            // Validações
            'nivel_validacao_id' => 1,
            //'status_validacao' => true,
            'status_defensivos' => 5,
            'status_especialidades' => 5,
            'status_negociacao_id' => 2,

            // Outros
            'observacoes' => 'Negociação de exemplo para testes iniciais.',
            'cotacao_moeda_usd_brl' => 5.00,
        ]);
    }
}
