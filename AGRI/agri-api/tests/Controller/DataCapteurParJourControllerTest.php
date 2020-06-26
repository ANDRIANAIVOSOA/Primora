<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

class DataCapteurParJourControllerTest extends WebTestCase
{
    private $sendingDateTimeStart = '2018-12-01 01:00:00';
    private $sendingDateTimeEnd = '2019-01-31 23:59:00';
    private $email = 'r.herriniaina@gmail.com';
    private $capteur = '92f8a295-96d0-4fdc-b334-8da29c6d0612';
    private $site = 1;
    private $yearFilter = 1;

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
     * @group DataParJourController
     * @group testRetourneDataCapteurParJoursDate
     */
    public function testRetourneDataCapteurParDates(): void
    {
        $this->client->request('GET', "/api/data-capteur/$this->sendingDateTimeStart/$this->sendingDateTimeEnd");
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertInternalType('object', $response);
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }


     /**
     * @group controller
     * @group DataParJourController
     * @group testRetourneDataCapteurParJoursListe
     */
    public function testRetourneListeDataCapteur(): void
    {
        $this->client->request(
            'GET',
            "/api/data-capteur",
            [
                'email' => $this->email
            ]);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertInternalType('object', $response);
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }


    /**
     * @group controller
     * @group DataParJourController
     * @group testRetourneDataCapteurParJoursGroupe
     */
    public function testRetourneDataCapteurGroupe(): void
    {
        $this->client->request(
            'GET',
            "/api/data-capteur-bycapteur",
            [
                'email' => $this->email
            ]);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $this->assertInternalType('object', $response);
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }



    /**
     * @group controller
     * @group DataParJourController
     * @group testRetourneDataCapteurParJoursFiltre
     */
    public function testRetourneDataCapteurFiltre(): void
    {
        $this->client->request(
            'POST',
            "/api/data-capteur/filter",
            [
                'startDate' => $this->sendingDateTimeStart,
                'endDate' => $this->sendingDateTimeEnd,
                'email' => $this->email,
                'capteur' => $this->capteur,
                'site' => $this->site,
                'yearFilter' => $this->yearFilter
            ]);
            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals("application/json", $response->headers->get('content-type'));
            $this->assertInternalType('object', $response);
            $data = json_decode($response->getContent());
            $this->assertAttributeSame(true, 'success', $data); 
    }

    
     /**
     * @group controller
     * @group DataParJourController
     * @group testInsertDataCapteurParJourController
     */
    public function testInsertDataCapteurParJour(): void
    {
        $aDatas = array(
            "data_capteur_par_jour" => array (
                0 => array(
                    'capteur' => '92f8a295-96d0-4fdc-b334-8da29c6d0612',
                    'deviceId'=>'gdvtevc85sz',
                    'level'=>12.4,
                    'sendingDateTime'=>'2019-02-18 15:13:06',
                    'siteId'=>1,
                    'isDeleted'=>0
                )
            )
        );
        
        $this->client->request(
            'POST',
            "/api/data-capteur/add",$aDatas       
        );
        
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $response->headers->get('content-type'));
        $data = json_decode($response->getContent());
        $this->assertAttributeSame(true, 'success', $data);
    }


    
}
