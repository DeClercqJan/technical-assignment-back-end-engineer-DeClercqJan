<?php
declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetProductControllerTest extends WebTestCase
{
    public function testGetProductControllerTest(): void
    {
        $client = static::createClient();

        $crawler = $client->getRequest('GET', '/api/products');

        $this->assertResponseIsSuccessful();
    }
}