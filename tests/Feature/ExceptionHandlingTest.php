<?php

namespace Tests\Feature;

use Tests\TestCase;

use PHPUnit\Framework\Attributes\Test;

class ExceptionHandlingTest extends TestCase
{
    #[Test]
    public function web_business_exception_returns_html_403()
    {
        $response = $this->get('/test-web');

        $response->assertStatus(403);
        $response->assertSee('User is not active');
    }

    #[Test]
    public function api_business_exception_returns_json_403()
    {
        $response = $this->getJson('/api/test-api');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'User is not active',
        ]);
    }

    #[Test]
    public function system_error_returns_500()
    {
        $response = $this->get('/test-bug');

        $response->assertStatus(500);
    }
}
