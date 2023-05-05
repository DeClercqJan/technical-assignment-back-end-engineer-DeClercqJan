<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebTestCaseBase extends WebTestCase
{
    protected EntityManagerInterface $em;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function register(string $email, string $password): Response
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password
            ])
        );
        return $this->client->getResponse();
    }

    protected function login(string $email, string $password): Response
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/login',
            [],
            [],
            [
            'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
            'email' => $email,
            'password' => $password
            ])
        );
        return $this->client->getResponse();
    }

    protected function getAccessToken(string $email, string $password): string
    {
        $response = $this->login($email, $password);
        $content = $response->getContent();
        $array = json_decode($content, true);
        return $array['token'];
    }


    protected function getRefreshTokenFromLogin(string $email, string $password): string
    {
        $response = $this->login($email, $password);
        $content = $response->getContent();
        $array = json_decode($content, true);
        return $array['refresh_token'];
    }
}