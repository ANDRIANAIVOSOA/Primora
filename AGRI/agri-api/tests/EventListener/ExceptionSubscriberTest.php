<?php

namespace App\Tests\EventListener;

use App\EventListener\ExceptionSubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriberTest extends TestCase
{
    private function getLoggerMock()
    {
        return $this->createMock(LoggerInterface::class);
    }

    private function getEventMock()
    {
        $exception = $this->createMock(HttpException::class);
        $exception->expects($this->any())
            ->method('getMessage')
            ->willReturn('Test error');
        $exception->expects($this->any())
            ->method('getStatusCode')
            ->willReturn(404);
        $exception->expects($this->any())
            ->method('getHeaders')
            ->willReturn([]);
        $exception->expects($this->any())
            ->method('getFile')
            ->willReturn('Test file');
        $exception->expects($this->any())
            ->method('getLine')
            ->willReturn('Line 0');
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('getPathInfo')
            ->willReturn('/api/login');
        $event = $this->createMock(GetResponseForExceptionEvent::class);
        $event->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);
        $event->expects($this->any())
            ->method('getException')
            ->willReturn($exception);
        return $event;
    }

    /**
     * @group eventlistener
     * @group exceptionsubscriber
     */
    public function testGetsubscribedeventsRetourneArray(): void
    {
        $events = ExceptionSubscriber::getSubscribedEvents();
        $this->assertEquals([
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
            ]
        ], $events);
    }

    /**
     * @group eventlistener
     * @group exceptionsubscriber
     */
    public function testLogexceptionDevraitAppelerCritical(): void
    {
        $event = $this->getEventMock();
        $logger = $this->getLoggerMock();
        $logger->expects($this->once())
            ->method('critical')
            ->with($event->getException()->getMessage());
        $exception = new ExceptionSubscriber($logger);
        $exception->logException($event);
    }

    /**
     * @group eventlistener
     * @group exceptionsubscriber
     */
    public function testProcessexceptionDevraitRetournerUnJsonSiApi(): void
    {
        $event = $this->getEventMock();
        $event->expects($this->once())
            ->method('setResponse');
        $logger = $this->getLoggerMock();
        $exception = new ExceptionSubscriber($logger);
        $exception->processException($event);
    }

    /**
     * @group eventlistener
     * @group exceptionsubscriber
     */
    public function testProcessexceptionNeFeraAucunTraitement(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn('/');
        $event = $this->createMock(GetResponseForExceptionEvent::class);
        $event->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);
        $event->expects($this->never())
            ->method('setResponse');
        $logger = $this->getLoggerMock();
        $exception = new ExceptionSubscriber($logger);
        $exception->processException($event);
    }
}
