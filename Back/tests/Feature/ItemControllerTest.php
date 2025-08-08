<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Itens;
use App\Models\Setor;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_pode_criar_um_item_novo_e_atualizar_estoque_de_outro_com_o_mesmo_nome()
    {
        $setor = Setor::factory()->create();

        // 1. Cria o primeiro item
        $response1 = $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Cabo HDMI 2.0 1.5 metros',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response1->assertStatus(201);
        $response1->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'nome',
                'codigo',
                'descricao',
                'created_at',
                'updated_at',
                'estoque' => [
                    'id',
                    'item_id',
                    'quantidade',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        // 2. Cria item com mesmo nome, código diferente
        $response2 = $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-002',
            'descricao' => 'Cabo HDMI 2.0 3 metros',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response2->assertStatus(201);
        $response2->assertJson([
            'message' => 'Item criado.',
        ]);

        // Verificações de estoque
        $this->assertDatabaseCount('itens', 2);

        $item1 = Itens::where('codigo', 'HDMI-001')->first();
        $item2 = Itens::where('codigo', 'HDMI-002')->first();

        $this->assertEquals(2, $item1->estoque->quantidade); // foi incrementado
        $this->assertEquals(0, $item2->estoque->quantidade); // criado com 0
    }

    /** @test */
    public function nao_pode_criar_item_com_codigo_duplicado_e_nome_diferente()
    {
        $setor = Setor::factory()->create();

        $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Item original',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response = $this->postJson('/api/v1/create/itens', [
            'nome' => 'Outro nome',
            'codigo' => 'HDMI-001',
            'descricao' => 'Outro item com mesmo código',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'O código já está em uso por outro item.',
        ]);
    }

    /** @test */
    public function nao_pode_criar_item_com_nome_e_codigo_repetidos()
    {
        $setor = Setor::factory()->create();

        $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Item original',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response = $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Duplicado',
            'quantidade' => 1,
            'setor_id' => $setor->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Item já existe com esse nome e código.',
        ]);
    }

    /** @test */
    public function nao_deve_criar_item_com_dados_invalidos()
    {
        $response = $this->postJson('/api/v1/create/itens', [
            'nome' => '',
            'codigo' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome', 'codigo', 'quantidade', 'setor_id']);
    }
}
