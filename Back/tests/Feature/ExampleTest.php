<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_root_returns_api_message(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'API Laravel',
        ]);
}
}
