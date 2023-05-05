<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class RegisterControllerTest extends WebTestCaseBase
{
    public function testRegisterControllerTestSuccess(): void
    {
        $response = $this->register('weleenemail@test.be', 'nieuwwachtwoord');
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $array = json_decode($content, true);
        $this->assertArrayHasKey('uuid', $array);
    }

    public function testRegisterControllerNoEmail(): void
    {
        $response = $this->register('geenemail','nieuwwachtwoord');
        $content = $response->getContent();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($content);
    }
}