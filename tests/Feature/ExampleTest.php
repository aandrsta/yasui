<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the terms of service page is accessible.
     */
    public function test_terms_of_service_page_is_accessible(): void
    {
        $response = $this->get('/terms-of-service');
        $response->assertStatus(200);
        $response->assertSee('Ketentuan Layanan');
    }

    /**
     * Test that the privacy policy page is accessible.
     */
    public function test_privacy_policy_page_is_accessible(): void
    {
        $response = $this->get('/privacy-policy');
        $response->assertStatus(200);
        $response->assertSee('Kebijakan Privasi');
    }
}
