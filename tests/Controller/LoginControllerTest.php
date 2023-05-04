<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
    public function testLoginController(): void
    {
        $client = static::createClient();
        $client->request(
            Request::METHOD_GET,
            '/api/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'email' => 'hat@monopoly.com',
                'password' => 'hotels!'
            ])
        );
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $content = $response->getContent();
        $array = json_decode($content, true);
        $this->assertArrayHasKey('token', $array);
        $this->assertArrayHasKey('refresh_token', $array);
        $this->assertArrayHasKey('refresh_token_expiration', $array);
    }
}