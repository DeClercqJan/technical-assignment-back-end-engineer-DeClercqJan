<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetProductsControllerTest extends WebTestCase
{
    public function testGetProductsControllerTest(): void
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

        $accessToken = $array['token'];

        $client->request(
            Request::METHOD_GET,
            '/api/products',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response2 = $client->getResponse();
        $content2 = $response2->getContent();
        $this->assertEquals(Response::HTTP_OK, $response2->getStatusCode());
        $this->assertJson($content2);
        $array2 = json_decode($content2, true);
        $this->assertIsArray($array2);
        // see fixtures
        $this->assertCount(2, $array2);
        foreach ($array2 as $element) {
            $this->assertArrayHasKey('uuid', $element);
            $this->assertArrayHasKey('name', $element);
            $this->assertArrayHasKey('price', $element);
        }
    }
}