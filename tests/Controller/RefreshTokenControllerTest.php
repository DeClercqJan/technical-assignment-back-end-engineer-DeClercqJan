<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenControllerTest extends WebTestCaseBase
{
    public function testRefreshTokenControllerTestSuccess(): void
    {
        $this->login(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);
        $cookieJar = $this->client->getCookieJar();
        $this->assertNotEmpty($cookieJar->all());
        $this->client->request(
            Request::METHOD_GET,
            '/api/token/refresh',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $array = json_decode($content, true);
        $this->assertArrayHasKey('token', $array);
        $this->assertArrayHasKey('refresh_token', $array);
        $this->assertArrayHasKey('refresh_token_expiration', $array);
    }

    public function testRefreshTokenControllerTestNoRefreshTokenInCookie(): void
    {
        $cookieJar = $this->client->getCookieJar();
        $this->assertEmpty($cookieJar->all());
        $this->client->request(
            Request::METHOD_GET,
            '/api/token/refresh',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}