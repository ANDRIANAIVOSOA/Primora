<?php

namespace App\Service;

use App\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityService
{
    /**
     * @var TokenStorageInterface
     */
    public $securityToken;

    public function __construct(TokenStorageInterface $securityToken)
    {
        $this->securityToken = $securityToken;
    }

    /**
     * @return UserInterface
     */
    public function getConnectedUser(): UserInterface
    {
        // TODO : quoi retourné si user null
        $token = $this->securityToken->getToken();
        if ($token === null) {
            return new User();
        }
        $user = $token->getUser();
        if ( ! $user instanceof User) {
            return new User();
        }

        return $user;
    }
}
