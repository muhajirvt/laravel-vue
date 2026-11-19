<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_and_dashboard_paths_load_the_application(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/dashboard')->assertOk();
    }
}
