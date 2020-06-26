<?php

namespace App\Tests\Helpers;

use App\Helpers\StringHelpers;
use PHPUnit\Framework\TestCase;

class StringHelpersTest extends TestCase
{
    /**
     * @group helpers
     * @group stringhelpers
     */
    public function testGenerateuuidRetourneString(): void
    {
        $stringService = new StringHelpers();
        $uuid          = $stringService->generateUuid();
        $this->assertEquals(36, \strlen($uuid));
    }

    /**
     * @group helpers
     * @group stringhelpers
     */
    public function testGeneratealternativeuuidRetourneString(): void
    {
        $stringService = new StringHelpers();
        $uuid          = $stringService->generateAlternativeUuid();
        $this->assertEquals(36, \strlen($uuid));
    }
}
