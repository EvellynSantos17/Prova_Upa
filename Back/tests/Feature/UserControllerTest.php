<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setor;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_pode_ser_cadastrado_com_dados_validos()
    {
        // Cria o setor necessário para o usuário
        $setor = Setor::create([
            'nome' => 'TI',
        ]);

        $dados = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => 'Senha@123',
            'password_confirmation' => 'Senha@123',
            'setor_id' => 1,
        ];

        $response = $this->postJson('/api/v1/register', $dados);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'token',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'joao@example.com',
        ]);
    }
}
