<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @group entity
     * @group userentity
     */
    public function testUserEntity(): void
    {
        $user = new User();
        $this->assertEquals(36, \strlen($user->getId()));
    }
}