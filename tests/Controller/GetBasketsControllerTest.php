<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetBasketsControllerTest extends WebTestCaseBase
{
    public function testGetBasketsController(): void
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

        $this->client->request(
            Request::METHOD_POST,
            '/api/baskets',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $array = json_decode($content, true);
        $this->assertArrayHasKey('uuid', $array);
        $basketUuid = $array['uuid'];

        $this->client->request(
            Request::METHOD_POST,
            '/api/baskets',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ]
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $array = json_decode($content, true);
        $this->assertArrayHasKey('uuid', $array);
        $basketUuid2 = $array['uuid'];

        $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/baskets/%s/add-product', $basketUuid),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ],
            json_encode([
                'product_uuid' => $products[0]['uuid'],
                'amount' => 69
            ])
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $array = json_decode($content, true);
        $this->assertArrayHasKey('basket_item_uuid', $array);

        $this->client->request(
            Request::METHOD_GET,
            '/api/baskets',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ],
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $baskets = json_decode($content, true);
        $this->assertCount(2, $baskets);
        foreach ($baskets as $basket) {
            $this->assertArrayHasKey('uuid', $basket);
            $this->assertArrayHasKey('created_at', $basket);
            $this->assertArrayHasKey('checked_out_at', $basket);
            $this->assertArrayHasKey('deleted_at', $basket);
            $this->assertArrayHasKey('basket_items', $basket);
        }
    }
}