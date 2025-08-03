<?php

namespace Tests\Feature;

use App\Models\Setor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetorControllerTest extends TestCase
{
    use RefreshDatabase; // Reseta o banco de dados entre os testes

    /**
     * Testa se o método index retorna a lista de setores com sucesso.
     *
     * @return void
     */
    public function test_index_retorna_lista_de_setores()
    {
        // Cria alguns setores no banco de dados
        Setor::factory()->create(['nome' => 'Setor A']);
        Setor::factory()->create(['nome' => 'Setor B']);

        // Chama a rota do index
        $response = $this->get('/api/v1/setores');

        // Verifica se a resposta tem status 200
        $response->assertStatus(200);

        // Verifica se a resposta contém os setores criados
        $response->assertJsonFragment(['nome' => 'Setor A']);
        $response->assertJsonFragment(['nome' => 'Setor B']);
    }

    /**
     * Testa se o método index retorna uma lista vazia quando não há setores.
     *
     * @return void
     */
    public function test_index_retorna_lista_vazia_se_nao_houver_setores()
    {
        // Chama a rota do index quando não há setores
        $response = $this->get('/api/v1/setores');

        // Verifica se a resposta tem status 200
        $response->assertStatus(200);

        // Verifica se a resposta contém uma lista vazia
        $response->assertJson([]);
    }
}
