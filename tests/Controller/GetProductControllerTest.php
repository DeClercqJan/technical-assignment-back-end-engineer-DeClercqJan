<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetProductControllerTest extends WebTestCaseBase
{
    public function testGetProductControllerSuccess(): void
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

        $url = '/api/products/' . $products[0]['uuid'];
        $this->client->request(
            Request::METHOD_GET,
            $url,
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
        $product = json_decode($content, true);
        $this->assertIsArray($product);
        $this->assertCount(3, $product);
        $this->assertArrayHasKey('uuid', $product);
        $this->assertArrayHasKey('name', $product);
        $this->assertArrayHasKey('price', $product);
    }

    public function testGetProductControllerBadUuid(): void
    {
        $accessToken = $this->getAccessToken(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);

        $url = '/api/products/' . Uuid::uuid4();
        $this->client->request(
            Request::METHOD_GET,
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
