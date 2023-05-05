<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidateRefreshTokenControllerTest extends WebTestCaseBase
{
    public function testInvalidateRefreshTokenSuccess(): void
    {
        $this->login(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);
        $cookieJar = $this->client->getCookieJar();
        $this->assertNotEmpty($cookieJar->all());
        $this->client->request(
            Request::METHOD_GET,
            '/api/token/invalidate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testInvalidateNoRefreshTokenInCookie(): void
    {
        $cookieJar = $this->client->getCookieJar();
        $this->assertEmpty($cookieJar->all());
        $this->client->request(
            Request::METHOD_GET,
            '/api/token/invalidate',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}