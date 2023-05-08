<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetBasketItemsRemovedFromBasketBeforeCheckoutControllerTest extends WebTestCaseBase
{
    public function testGetBasketItemsRemovedFromBasketBeforeCheckoutController(): void
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
        $basketItemUuid = $array['basket_item_uuid'];

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/baskets/%s', $basketUuid),
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
        $basket = json_decode($content, true);
        $this->assertArrayHasKey('uuid', $basket);
        $this->assertArrayHasKey('created_at', $basket);
        $this->assertArrayHasKey('checked_out_at', $basket);
        $this->assertArrayHasKey('deleted_at', $basket);
        $this->assertArrayHasKey('basket_items', $basket);
        $basketItems = $basket['basket_items'];
        $this->assertCount(1, $basketItems);
        $this->assertArrayHasKey('uuid', $basketItems[0]);
        $this->assertArrayHasKey('basket_uuid', $basketItems[0]);
        $this->assertArrayHasKey('created_at', $basketItems[0]);
        $this->assertArrayHasKey('product', $basketItems[0]);
        $this->assertArrayHasKey('uuid', $basketItems[0]['product']);
        $this->assertArrayHasKey('name', $basketItems[0]['product']);
        $this->assertArrayHasKey('price', $basketItems[0]['product']);
        $this->assertArrayHasKey('amount', $basketItems[0]);
        $this->assertArrayHasKey('deleted_at', $basketItems[0]);
        $this->assertEquals($products[0]['uuid'], $basketItems[0]['product']['uuid']);
        $this->assertEquals(69, $basketItems[0]['amount']);

        $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/api/baskets/%s/basket-items/%s', $basketUuid, $basketItemUuid),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Authorization' => sprintf('bearer %s', $accessToken)
            ],
            json_encode([
                'amount' => 44
            ])
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $array = json_decode($content, true);


        $this->client->request(
            Request::METHOD_POST,
            sprintf('/api/baskets/%s/check-out', $basketUuid),
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
        $this->assertCount(1, $baskets);

        foreach ($baskets as $basket) {
            $this->assertArrayHasKey('uuid', $basket);
            $this->assertArrayHasKey('created_at', $basket);
            $this->assertArrayHasKey('checked_out_at', $basket);
            $this->assertArrayHasKey('deleted_at', $basket);
            $this->assertArrayHasKey('basket_items', $basket);
            $basketItems = $basket['basket_items'];
            $this->assertCount(1, $basketItems);
            $this->assertArrayHasKey('uuid', $basketItems[0]);
            $this->assertArrayHasKey('basket_uuid', $basketItems[0]);
            $this->assertArrayHasKey('created_at', $basketItems[0]);
            $this->assertArrayHasKey('product', $basketItems[0]);
            $this->assertArrayHasKey('uuid', $basketItems[0]['product']);
            $this->assertArrayHasKey('name', $basketItems[0]['product']);
            $this->assertArrayHasKey('price', $basketItems[0]['product']);
            $this->assertArrayHasKey('amount', $basketItems[0]);
            $this->assertArrayHasKey('deleted_at', $basketItems[0]);
            $this->assertEquals($products[0]['uuid'], $basketItems[0]['product']['uuid']);
            $this->assertEquals(69, $basketItems[0]['amount']);
            $this->assertNotEmpty($basketItems[0]['deleted_at']);
            $this->assertNotEmpty($basket['checked_out_at']);
        }
    }
}