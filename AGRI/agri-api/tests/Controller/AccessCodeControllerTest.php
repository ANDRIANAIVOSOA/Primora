<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

class AccessCodeControllerTest extends WebTestCase
{
    /**
     * @group controller
     * @group testAccessCodeController
     */
    public function testRetourneListeAccessCode(): void
    {
        $client = static::createClient();

        $request = $client->request('GET', '/api/access-code/list');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }

    /**
     * @group controller
     * @group testAccessCodeController
     */
     
    public function testInsertAccessCode(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            "/api/access-code/add",
            [
                'description' => 'LOMAY'
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @group controller
     * @group testAccessCodeController
     */
     
    public function testModifAccessCode(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            "/api/access-code/put/77510e6f-905c-40ca-946b-d404cffa7033",
            [
                'description' => 'SAYNA'
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @group controller
     * @group testAccessCodeController
     */
     
    public function testSupprimeAccessCode(): void
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            "/api/access-code/4f468b96-a96b-495d-8067-d14a0bed5a5f"
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertInternalType('object', $response);
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }
}
