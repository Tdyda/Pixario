<?php

namespace App\Api\Security\Authenticator;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Port\AuthUserProviderInterface;
use App\Infrastructure\Security\User\AnonymousUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CookieJwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly AuthUserProviderInterface $repo
    ) {
    }

    public function supports(Request $request): ?bool
    {
        $path = $request->getPathInfo();

        return str_starts_with($path, '/api')
            && !preg_match('#^/api/gallery/get($|/)#', $path)
            && $path !== '/api/processed/image/webhook';
    }


    public function authenticate(Request $request): Passport
    {
        $accessToken = $request->cookies->get('access_token') ?? null;

        if (!$accessToken) {
            throw new InvalidTokenException('Brak access_token');
        }

        $payload = $this->jwtService->decode($accessToken);

        if ($payload['type'] ?? null) {
            return new SelfValidatingPassport(
                new UserBadge('anonymous', fn() => new AnonymousUser()),
            );
        }

        if (!$payload || !isset($payload['username'])) {
            throw new InvalidTokenException();
        }

        return new SelfValidatingPassport(
            new UserBadge($payload['username'], fn($email) => $this->repo->findByEmail($email))
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