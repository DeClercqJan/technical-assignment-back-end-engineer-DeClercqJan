<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterControllerTest(): void
    {
        $client = static::createClient();
        $client->request(
            Request::METHOD_POST,
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'nieuwe2@email.be',
                'password' => 'nieuwwachtwoord'
            ])
        );

        $response = $client->getResponse();
        $content  = $response->getContent();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $array = json_decode($content, true);
        $this->assertArrayHasKey( 'uuid', $array);
    }
}