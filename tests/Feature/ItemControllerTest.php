<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_pode_criar_um_item_com_dados_validos()
    {
        // Se a rota estiver protegida por autenticação, crie um usuário:
        // $user = User::factory()->create();
        // $this->actingAs($user);

        // Dados simulados válidos
        $dados = [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001',
            'descricao' => 'Cabo HDMI 2.0 1.5 metros',
        ];

        $response = $this->postJson('/api/v1/create/itens', $dados);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'nome',
                'codigo',
                'descricao',
                'created_at',
                'updated_at',
            ]
        ]);

        // Verifica se o item foi inserido no banco
        $this->assertDatabaseHas('itens', [
            'nome' => 'Cabo HDMI',
            'codigo' => 'HDMI-001'
        ]);
    }

    /** @test */
    public function nao_deve_criar_item_com_dados_invalidos()
    {
        $dadosInvalidos = [
            'nome' => '', // nome vazio
            'codigo' => '', // código vazio
        ];

        $response = $this->postJson('/api/v1/create/itens', $dadosInvalidos);

        $response->assertStatus(422); // Erro de validação
        $response->assertJsonValidationErrors(['nome', 'codigo']);
    }
}
