<?php

namespace App\Tests\Service;

use App\Service\DefaultService;
use PHPUnit\Framework\TestCase;

class DefaultServiceTest extends TestCase
{
    /**
     * @group service
     * @group defaultservice
     */
    public function testGethometextRetourneLeTexteCorrecte(): void
    {
        $defaultService = new DefaultService();
        $this->assertEquals(
            'Hello from api',
            $defaultService->getHomeText()
        );
    }
}
