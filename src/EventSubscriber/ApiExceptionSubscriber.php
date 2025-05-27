<?php

namespace App\EventSubscriber;

use App\DTO\Response\ErrorResponse;
use App\Exception\ForbiddenActionException;
use App\Exception\InvalidCredentialsException;
use App\Exception\InvalidTokenException;
use App\Exception\UserAlreadyExistsException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private const EXCEPTION_STATUS_MAP = [
        UserAlreadyExistsException::class => 409,
        InvalidCredentialsException::class => 401,
        ForbiddenActionException::class => 403,
        InvalidTokenException::class => 401,
        UserNotFoundException::class => 404,
    ];

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $exceptionClass = get_class($exception);

        if (isset(self::EXCEPTION_STATUS_MAP[$exceptionClass])) {
            $event->setResponse(new JsonResponse(
                new ErrorResponse($exception->getMessage()),
                self::EXCEPTION_STATUS_MAP[$exceptionClass]
            ));
        } elseif ($exception instanceof HttpExceptionInterface) {
            $event->setResponse(new JsonResponse(
                new ErrorResponse($exception->getMessage()),
                $exception->getStatusCode()
            ));
        } else {
            $event->setResponse(new JsonResponse(
                new ErrorResponse('Wewnętrzny błąd serwera'),
                500
            ));
        }
    }
}