<?php

namespace App\Tests\Main\Infrastructure\Controller\Health;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckControllerTest extends WebTestCase
{
    public function tearDown(): void
    {
        restore_exception_handler();
        parent::tearDown();
    }


    public function testCheckController()
    {
        // integracion
        $client = static::createClient();

        $client->request('GET', '/check');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($content['data']);
        $this->assertEquals("success", $content['message']);
        $this->assertEquals(200, $content['status']);
    }
}
