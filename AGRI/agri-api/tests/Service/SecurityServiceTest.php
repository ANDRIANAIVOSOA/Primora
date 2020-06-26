<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\SecurityService;
use FOS\UserBundle\Model\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SecurityServiceTest extends TestCase
{
    /**
     * @group service
     * @group securityservice1
     */
    public function testGetconnecteduserRetourneEmptyUser()
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(null);
        $securityService = new SecurityService($tokenStorage);
        $user            = $securityService->getConnectedUser();
        $this->assertNull($user->getUsername());
    }

    /**
     * @group service
     * @group securityservice
     */
    public function testGetconnecteduserRetourneUser()
    {
        $user = new User();
        $user->setUsername('demo');

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token);
        $securityService = new SecurityService($tokenStorage);
        $user            = $securityService->getConnectedUser();
        $this->assertEquals('demo', $user->getUsername());
    }

    /**
     * @group service
     * @group securityservice
     */
    public function testGetconnecteduserRetourneNotUser()
    {
        $user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token);
        $securityService = new SecurityService($tokenStorage);
        $user            = $securityService->getConnectedUser();
        $this->assertNull($user->getUsername());
    }
}
