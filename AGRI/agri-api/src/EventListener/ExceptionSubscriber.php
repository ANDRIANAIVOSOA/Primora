<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
            ]
        ];
    }

    public function processException(GetResponseForExceptionEvent $event): void
    {
        if (strpos($event->getRequest()->getPathInfo(), '/api/') !== 0) {
            return;
        }
        // Force l'envoie d'un json si appel api
        $exception = $event->getException();
        $message = [
            'success' => false,
            'code' => ($exception instanceof HttpExceptionInterface) ? $exception->getStatusCode() : null,
            'message' => $exception->getMessage(),
        ];
        if (getenv('APP_ENV') !== 'prod') {
            $message['file'] = $exception->getFile();
            $message['line'] = $exception->getLine();
        }
        $response = new JsonResponse($message);
        $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }

    public function logException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        $this->logger->critical($exception->getMessage(), [
            // include extra "context" info in your logs
            'cause' => sprintf(
                'File %s, line %s',
                $exception->getFile(),
                $exception->getLine()
            ),
        ]);
    }
}
