<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Service\JwtService;
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
    public function __construct(private JwtService $jwtService, private UserRepository $repo)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->cookies->has('access_token');
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = $request->cookies->get('access_token');
        $payload = $this->jwtService->decode($accessToken);

        if (!$payload || !isset($payload['username'])) {
            throw new AuthenticationException('Invalid token');
        }

        return new SelfValidatingPassport(
            new UserBadge($payload['username'], fn($email) => $this->repo->findOneBy(['email' => $email]))
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(['error' => 'Unauthorized'], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?JsonResponse
    {
        return null;
    }
}
