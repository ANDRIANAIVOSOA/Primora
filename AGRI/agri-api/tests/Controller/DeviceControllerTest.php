<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

class DeviceControllerTest extends WebTestCase
{
    private $accessCode = '911647f7-1ea3-4672-bf05-b89a319364ab';
    private $deviceId = 'FD25FE-GG4HT5-GHRH';
    private $idDevice = '47vcgvyc';
    private $deleteId = 'ge1nerer5Auto';


    /**
     * @var Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }


    /**
     * @group controller
     * @group deviceController
     * @group testDeviceControllerId
     */
    public function testRetourneDeviceParId(): void
    {
        $this->client->request('GET', "/api/device/$this->deviceId");
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);    
    }

    /**
     * @group controller
     * @group deviceController
     * @group testInsertDeviceController
     */
     
    public function testInsertDevice(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            "/api/device/add",
            [
                'id'=>'ge1nerer5Auto',
                'site' => 1,
                'agentName'=>'Erick',
                'accessCode'=>'74aaeb22-8dec-45b4-abb8-d28a474d37f5'
            ]
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }

    /**
     * @group controller
     * @group deviceController
     * @group testPutDeviceController
     */
    public function testPutDevice(): void
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            "/api/device/$this->idDevice",
            [
                'id'=>"gdvtevc85sz",
                'site' => 1,
                'agentName'=>'Josoa',
                'accessCode'=>'e0f197c7-1024-4170-94e0-8206dfffbc80'
            ]
        );
        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }


    /**
     * @group controller
     * @group deviceController
     * @group testDeleteDeviceController
     */
    public function testSupprimeDevice(): void
    {
        $client = static::createClient();
        $client->request(
            'DELETE',
            "/api/device/$this->deleteId"
        );
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertInternalType('object', $response);
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }
}
