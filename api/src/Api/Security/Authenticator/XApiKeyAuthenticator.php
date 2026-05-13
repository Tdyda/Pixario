<?php

namespace App\Api\Security\Authenticator;

use App\Api\Security\Exception\InvalidApiKeyException;
use App\Infrastructure\Security\User\ApiKeyUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class XApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly string $apiKey
    ) {
    }

    public function supports(Request $request): ?bool
    {
        $path = $request->getPathInfo();

        return str_starts_with($path, '/webhooks');
    }


    public function authenticate(Request $request): Passport
    {
        $apiKey = $request->cookies->get('x-api-key') ?? null;

        if (!$apiKey) {
            throw new InvalidApiKeyException('Missing X-Api-Key');
        }

        if ($this->apiKey !== $apiKey) {
            throw new InvalidApiKeyException('Invalid X-Api-Key');
        }

        return new SelfValidatingPassport(
            new UserBadge('api-key', fn() => new ApiKeyUser())
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'error' => $exception->getMessage()
        ], 401);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?JsonResponse {
        return null;
    }
}