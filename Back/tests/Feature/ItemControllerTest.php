<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Itens;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_pode_criar_um_item_novo_ou_incrementar_estoque()
    {
        $dados = [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Cabo HDMI 2.0 1.5 metros',
        ];

        $response1 = $this->postJson('/api/v1/create/itens', $dados);
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

        $response2 = $this->postJson('/api/v1/create/itens', [
            'nome' => 'Cabo HDMI',
            'descricao' => 'Cabo HDMI 2.0 1.5 metros',
            'codigo' => 'HDMI-002',
        ]);

        $response2->assertStatus(201);
        $response2->assertJson([ 
            'message' => 'Novo item criado com nome já existente. Estoque iniciado com 1.',
        ]);

        
        $this->assertEquals(2, Itens::count());

        $item1 = Itens::where('nome', 'Cabo HDMI')->first();
        $this->assertEquals(1, $item1->estoque->quantidade);


        $item2 = Itens::where('codigo', 'HDMI-002')->first();
        $this->assertEquals(1, $item2->estoque->quantidade);
    }

    /** @test */
    public function nao_deve_criar_item_com_dados_invalidos()
    {
        $dadosInvalidos = [
            'nome' => '',     // nome vazio
            'codigo' => '',   // código vazio
        ];

        $response = $this->postJson('/api/v1/create/itens', $dadosInvalidos);

        $response->assertStatus(422); // Erro de validação
        $response->assertJsonValidationErrors(['nome', 'codigo']);
    }
}
