<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProviderService implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadUserByUsername($username): UserInterface
    {
        return $this->fetchUser($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', \get_class($user))
            );
        }
        $username = $user->getUsername();
        return $this->fetchUser($username);
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    /**
     * @param string $username
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    private function fetchUser(string $username): UserInterface
    {
        /** @var User $user */
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy([
                'username' => $username
            ]);
        if (! $user instanceof User) {
            throw new UsernameNotFoundException('');
        }
        return $user;
    }

    /**
     * @param array $currentPayload
     * @return array
     */
    public function getCustomPayload(array $currentPayload): array
    {
        $currentPayload['cty'] = 'JWT';
        return $currentPayload;
    }
}
