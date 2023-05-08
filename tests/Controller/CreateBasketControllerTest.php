<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateBasketControllerTest extends WebTestCaseBase
{
    public function testCreateBasketControllerSuccess(): void
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
    }
}