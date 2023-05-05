<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCaseBase
{
    public function testLoginControllerSuccess(): void
    {
        $response = $this->login(UserFixture::VALID_EMAIL, UserFixture::VALID_PASSWORD);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $content = $response->getContent();
        $array = json_decode($content, true);
        $this->assertArrayHasKey('token', $array);
        $this->assertArrayHasKey('refresh_token', $array);
        $this->assertArrayHasKey('refresh_token_expiration', $array);
    }

    public function testLoginControllerBadPassword(): void
    {
        $response = $this->login('hat@monopoly.com', 'huisjes!');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}