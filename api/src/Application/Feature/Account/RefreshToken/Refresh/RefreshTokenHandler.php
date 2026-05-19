<?php

namespace App\Application\Feature\Account\RefreshToken\Refresh;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Api\Security\Mercure\MercureTokenFactory;
use App\Application\Feature\Account\RefreshToken\Create\CreateRefreshTokenHandler;
use App\Application\Feature\Account\SignIn\AuthTokensDto;
use App\Application\Port\AuthUserProviderInterface;
use App\Application\Port\RefreshTokenRepositoryInterface;

final readonly class RefreshTokenHandler
{
    public function __construct(
        private RefreshTokenRepositoryInterface $repository,
        private AuthUserProviderInterface $authProvider,
        private JwtService $jwtService,
        private CreateRefreshTokenHandler $createRefreshTokenHandler,
        private MercureTokenFactory $mercureTokenFactory
    ) {
    }

    public function handle(string $token): AuthTokensDto
    {
        $refreshToken = $this->repository->findByToken($token);

        $now = new \DateTimeImmutable();
        if (!$refreshToken || $refreshToken->getExpiresAt() < $now || $refreshToken->getRevokedAt() !== null) {
            throw new InvalidTokenException("Refresh token jest nieprawidłowy lub wygasł!");
        }

        $userId = $refreshToken->getUserId();
        $authUser = $this->authProvider->findByUserId($userId);

        $this->repository->revoke($refreshToken);
        $accessToken = $this->jwtService->createAccessToken($authUser);
        $refreshToken = $this->createRefreshTokenHandler->createAndPersistRefreshToken($authUser->id);

        $mercureToken = $this->mercureTokenFactory->createSubscriberToken($authUser->id);
        return new AuthTokensDto($accessToken, $refreshToken, $mercureToken);
    }
}