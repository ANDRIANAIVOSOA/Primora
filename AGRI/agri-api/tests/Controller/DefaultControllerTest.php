<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @group controller
     * @group defaultcontroller
     */
    public function testLaPageDAcceuilRetouneUnStatut200UneEnteteApplicationJsonEtUnBodyJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf(
            JsonResponse::class,
            $response
        );
        $this->assertEquals(
            '["Hello from api"]',
            $response->getContent()
        );
    }
}
