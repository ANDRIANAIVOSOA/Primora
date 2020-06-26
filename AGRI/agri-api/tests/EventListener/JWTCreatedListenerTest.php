<?php

namespace App\Tests\EventListener;

use App\EventListener\JWTCreatedListener;
use App\Service\UserProviderService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use PHPUnit\Framework\TestCase;

class JWTCreatedListenerTest extends TestCase
{
    /**
     * @group eventlistener
     * @group jwtcreatedlistener
     */
    public function testOnjwtcreatedChangeEvent(): void
    {
        $payloadData = [
            'message' => 'hi',
        ];
        $afterPayloadData = [
            'message' => 'hi',
            'cty' => 'JWT',
        ];
        $event = $this->createMock(JWTCreatedEvent::class);
        $event->expects($this->any())
            ->method('getData')
            ->willReturn($payloadData);
        $event->expects($this->once())
            ->method('setData')
            ->with($afterPayloadData);
        $userProvider = $this->createMock(UserProviderService::class);
        $userProvider->expects($this->once())
            ->method('getCustomPayload')
            ->with($payloadData)
            ->willReturn($afterPayloadData);
        $listener = new JWTCreatedListener($userProvider);
        $listener->onJWTCreated($event);
    }
}
