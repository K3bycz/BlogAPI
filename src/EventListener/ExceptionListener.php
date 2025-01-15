<?php

namespace App\EventListener;

use App\Exception\BlogAccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if ($exception instanceof BlogAccessDeniedException) {
            $response = new JsonResponse([
                'error' => $exception->getMessage(),
                'status' => 'error',
                'code' => $exception->getCode()
            ], $exception->getCode());
            
            $event->setResponse($response);
        }
    }
}