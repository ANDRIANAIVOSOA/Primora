<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\View\View;

class SiteControllerTest extends WebTestCase
{
    private $accessCode = '911647f7-1ea3-4672-bf05-b89a319364ab';

    /**
     * @group controller
     * @group testSiteController
     */
    public function testRetourneSiteParCodeDAccess(): void
    {
        $client = static::createClient();

        $client->request('GET', "/api/site/ac/$this->accessCode");
        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
