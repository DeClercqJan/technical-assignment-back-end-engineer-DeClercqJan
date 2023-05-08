<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteBasketControllerTest extends WebTestCaseBase
{
    public function testDeleteBasketController(): void
    {
        $accessToken = $this->getAccessToken(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);

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
            Request::METHOD_DELETE,
            sprintf('/api/baskets/%s', $basketUuid),
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
    }
}