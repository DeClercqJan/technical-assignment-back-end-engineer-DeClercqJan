<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetProductsControllerTest extends WebTestCaseBase
{
    public function testGetProductsControllerTestSuccess(): void
    {
        $accessToken = $this->getAccessToken(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);
        $this->client->request(
            Request::METHOD_GET,
            '/api/products',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $products = json_decode($content, true);
        $this->assertIsArray($products);
        // see fixtures
        $this->assertCount(5, $products);
        foreach ($products as $product) {
            $this->assertArrayHasKey('uuid', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
        }
    }

    public function testGetProductsControllerInvalidAccessToken(): void
    {
        $accessToken = 'ongeldige accesstoken';
        $this->client->request(
            Request::METHOD_GET,
            '/api/products',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}