<?php

namespace App\Application\Feature\Account\RefreshToken\Refresh;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Port\AuthUserProviderInterface;
use App\Infrastructure\Persistence\Repository\RefreshTokenRepository;

final readonly class RefreshTokenHandler
{
    public function __construct(
        private RefreshTokenRepository $repository,
        private AuthUserProviderInterface $authProvider,
        private JwtService $jwtService
    ) {
    }

    public function handle(string $token): string
    {
        $tokenEntity = $this->repository->findByToken($token);

        $now = new \DateTimeImmutable();
        if (!$tokenEntity || $tokenEntity->getExpiresAt() < $now) {
            throw new InvalidTokenException("Refresh token jest nieprawidłowy lub wygasł!");
        }

        $userId = $tokenEntity->getUserId();
        $authUser = $this->authProvider->findByUserId($userId);

        return $this->jwtService->createAccessToken($authUser);
    }
}