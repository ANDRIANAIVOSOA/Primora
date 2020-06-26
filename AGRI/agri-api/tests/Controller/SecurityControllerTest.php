<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @group controller
     * @group securitycontroller
     */
    public function testPageLogin(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertCount(
            1,
            $crawler->filter('form[action="/login_check"]')
        );
        $this->assertCount(
            1,
            $crawler->filter('input[name=_username][type=text]')
        );
        $this->assertCount(
            1,
            $crawler->filter('input[name=_password][type=password]')
        );
        $this->assertCount(
            1,
            $crawler->filter('input[name=_submit][type=submit]')
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @group controller
     * @group securitycontroller
     */
    public function testPageLoginSubmitForm(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form              = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $postCrawler = $client->submit($form);
        $this->assertContains(
            'Redirecting to',
            $postCrawler->filter('html')->text()
        );
    }
}
