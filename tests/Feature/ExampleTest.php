<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_api_returns_itens_successfully(): void
    {
        $response = $this->get('/api/itens');

        $response->assertStatus(200);
        $response->assertJsonStructure(['itens']); // se a resposta for do tipo ['itens' => [...]]
    }
}
