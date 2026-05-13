<?php

namespace App\Api\Security\Listener;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AnonymousJwtListener
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $token = $request->cookies->get('access_token');

        if (!$token) {
            throw new InvalidTokenException();
        }

        $payload = $this->jwtService->decode($token);

        if (($payload['type'] ?? null) === 'anonymous') {
            $request->attributes->set('anonymous_jwt', $payload);
        }
    }
}