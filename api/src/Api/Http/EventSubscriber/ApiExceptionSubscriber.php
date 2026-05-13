<?php

namespace App\Api\Http\EventSubscriber;

use App\Api\Http\Exception\ForbiddenActionException;
use App\Api\Http\Exception\InvalidCredentialsException;
use App\Api\Http\Exception\UserAlreadyExistsException;
use App\Api\Http\Exception\ValidationException;
use App\Api\Http\Response\ErrorResponse;
use App\Api\Security\Exception\InvalidTokenException;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private const EXCEPTION_MAP = [
        UserAlreadyExistsException::class => [
            'status' => 409,
            'code' => 'USER_ALREADY_EXISTS',
        ],
        InvalidCredentialsException::class => [
            'status' => 401,
            'code' => 'INVALID_CREDENTIALS',
        ],
        ForbiddenActionException::class => [
            'status' => 403,
            'code' => 'FORBIDDEN_ACTION',
        ],
        InvalidTokenException::class => [
            'status' => 401,
            'code' => 'INVALID_TOKEN',
        ],
        UserNotFoundException::class => [
            'status' => 404,
            'code' => 'USER_NOT_FOUND',
        ],
        ResourceNotFoundException::class => [
            'status' => 404,
            'code' => 'RESOURCE_NOT_FOUND',
        ],
        RandomException::class => [
            'status' => 500,
            'code' => 'RANDOM_ERROR',
        ],
    ];

    public function __construct(
        private readonly string $environment,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $validationException = $this->findValidationException($exception);

        if ($validationException !== null) {
            $details = [];

            foreach ($validationException->getViolations() as $violation) {
                $field = $violation->getPropertyPath();
                $details[$field][] = $violation->getMessage();
            }

            $event->setResponse(
                new JsonResponse(
                    ErrorResponse::validation($details),
                    422
                )
            );

            return;
        }

        $exceptionClass = $exception::class;

        if (isset(self::EXCEPTION_MAP[$exceptionClass])) {
            $meta = self::EXCEPTION_MAP[$exceptionClass];

            $event->setResponse(
                new JsonResponse(
                    new ErrorResponse(
                        message: $exception->getMessage(),
                        code: $meta['code'],
                        status: $meta['status'],
                    ),
                    $meta['status']
                )
            );

            return;
        }

        if ($exception instanceof ValidationException) {
            $event->setResponse(
                new JsonResponse(
                    ErrorResponse::validation($exception->getErrors()),
                    422
                )
            );

            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();

            $event->setResponse(
                new JsonResponse(
                    new ErrorResponse(
                        message: $exception->getMessage(),
                        code: 'HTTP_ERROR',
                        status: $status,
                    ),
                    $status
                )
            );

            return;
        }

        $message = $this->environment === 'dev'
            ? $exception->getMessage()
            : 'Wewnętrzny błąd serwera';

        $event->setResponse(
            new JsonResponse(
                new ErrorResponse(
                    message: $message,
                    code: 'INTERNAL_SERVER_ERROR',
                    status: 500,
                ),
                500
            )
        );
    }

    private function findValidationException(\Throwable $exception): ?ValidationFailedException
    {
        while ($exception !== null) {
            if ($exception instanceof ValidationFailedException) {
                return $exception;
            }

            $exception = $exception->getPrevious();
        }

        return null;
    }
}