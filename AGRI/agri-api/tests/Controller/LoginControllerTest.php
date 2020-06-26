<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * LoginControllerTest
 */
class LoginControllerTest extends WebTestCase
{
    private $accessCode = '911647f7-1ea3-4672-bf05-b89a319364ab';
    /**
     * @param JsonResponse|Response $response
     */
    protected function checkTokenFromResponse($response)
    {
        $this->assertInstanceOf(JsonResponse::class, $response);

        $content = json_decode($response->getContent());
        $this->assertObjectHasAttribute('token', $content);
        $this->assertTrue(is_string($content->token) && !empty($content->token));

        $t = explode('.', $content->token);
        $this->assertCount(3, $t);

        $payload = json_decode(base64_decode($t[1]));
        $this->assertObjectHasAttribute('id', $payload);
        $this->assertObjectHasAttribute('username', $payload);
        $this->assertObjectHasAttribute('iat', $payload);
        $this->assertObjectHasAttribute('exp', $payload);

        $this->assertTrue(is_int($payload->id));
        $this->assertTrue(is_int($payload->iat));
        $this->assertTrue(is_int($payload->exp));
        $this->assertTrue($payload->exp > $payload->iat);
    }

    /**
     * @group controller
     * @group apiLogin
     */
    public function testLoginPost()
    {
        /*$client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [
                'email' => 'r.herriniaina@gmail.com',
                'plainPassword' => [
                    'first' => 'heriniaina'
                ]
            ]
        );

        $response = $client->getResponse();
        /*$this->checkTokenFromResponse($response);
        $this->assertEquals(200, $response->getStatusCode());

        /*$response = $client->getResponse();
        $this->checkTokenFromResponse($response);*/
    }

    /**
     * @group controller
     * @group testRegisterUser
     */
    public function testRegisterUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/register',
            [
                'email' => 'randriatsiferanj@gmail.com',
                'username' => 'Josoa',
                'plainPassword' => [
                    'first' => 'Josoa123',
                    'second' => 'Josoa123'
                ],
                'accessCode' => $this->accessCode
            ]
        );

        $response = $client->getResponse();
        var_dump($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
    }
}