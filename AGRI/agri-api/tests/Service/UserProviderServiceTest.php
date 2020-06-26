<?php

namespace App\Tests\Security;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use App\Service\UserProviderService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderServiceTest extends TestCase
{
    private $mockEmail = 'demo';

    private function mockRepoAndEm($repoReturn = null): array
    {
        // Mock the user repository
        $userRepository = $this->createMock(ObjectRepository::class);

        $userRepository->expects($this->any())
                       ->method('findOneBy')
                       ->with(['username' => $this->mockEmail])
                       ->willReturn($repoReturn);

        // Mock the entity manager
        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);
        return [$userRepository, $em];
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testLoaduserbyusernameRetourneUserinterface(): void
    {
        $demo = new User();
        $demo->setUsername($this->mockEmail);
        [, $em] = $this->mockRepoAndEm($demo);

        $wsUserProvider = new UserProviderService($em);
        $user           = $wsUserProvider->loadUserByUsername($this->mockEmail);
        $this->assertEquals($this->mockEmail, $user->getUsername());
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testLoaduserbyusernameThrowExceptionUsernamenotfoundexception(): void
    {
        [, $em] = $this->mockRepoAndEm(null);
        $wsUserProvider = new UserProviderService($em);
        $this->expectException(UsernameNotFoundException::class);
        $wsUserProvider->loadUserByUsername($this->mockEmail);
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testRefreshuserRetourneUserinterface(): void
    {
        $demo = new User();
        $demo->setUsername($this->mockEmail);
        [, $em] = $this->mockRepoAndEm($demo);
        $wsUserProvider = new UserProviderService($em);
        $user           = $wsUserProvider->refreshUser($demo);
        $this->assertEquals($this->mockEmail, $user->getUsername());
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testRefreshuserThrowUnsupporteduserexception(): void
    {
        $demo = $this->createMock(UserInterface::class);
        [, $em] = $this->mockRepoAndEm($demo);
        $wsUserProvider = new UserProviderService($em);
        $this->expectException(UnsupportedUserException::class);
        $wsUserProvider->refreshUser($demo);
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testSupportsclassRetourneTrue(): void
    {
        [, $em] = $this->mockRepoAndEm(null);
        $wsUserProvider = new UserProviderService($em);
        $this->assertTrue($wsUserProvider->supportsClass(User::class));
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testSupportsclassRetourneFalse(): void
    {
        [, $em] = $this->mockRepoAndEm(null);
        $wsUserProvider = new UserProviderService($em);
        $this->assertFalse($wsUserProvider->supportsClass(UserInterface::class));
    }

    /**
     * @group service
     * @group userproviderservice
     */
    public function testGetcustompayloadRetourneArray(): void
    {
        [, $em] = $this->mockRepoAndEm(null);
        $wsUserProvider = new UserProviderService($em);
        $payload        = $wsUserProvider->getCustomPayload(['message' => 'hi']);
        $this->assertEquals([
            'message' => 'hi',
            'cty' => 'JWT',
        ], $payload);
    }
}
