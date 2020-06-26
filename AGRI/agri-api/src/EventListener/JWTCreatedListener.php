<?php

namespace App\EventListener;

use App\Service\UserProviderService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * @var UserProviderService
     */
    private $userProvider;

    public function __construct(UserProviderService $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $event->setData($this->userProvider->getCustomPayload($payload));
    }
}
