<?php

namespace App\Framework\EventListener;

use App\Framework\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse([
            'error' => $exception->getMessage()
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } elseif ($exception instanceof ValidatorException) {
            $response = new JsonResponse([
                'error' => 'Validation error',
                'violations' => $exception->getViolations()
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
