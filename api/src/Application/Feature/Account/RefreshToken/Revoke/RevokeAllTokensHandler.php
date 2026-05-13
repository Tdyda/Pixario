<?php

namespace App\Application\Feature\Account\RefreshToken\Revoke;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Port\RefreshTokenRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;

class RevokeAllTokensHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly JwtService $jwtService
    ) {
    }

    public function Handle(string $accessToken): void
    {
        $decoded = $this->jwtService->decode($accessToken);
        $email = $decoded['username'] ?? null;

        if (!$decoded || !$email) {
            throw new InvalidTokenException();
        }

        $user = $this->userRepository->findByEmailWithRefreshTokens($email);
        $refreshTokens = $user->getRefreshTokens();

        if ($refreshTokens->isEmpty()) {
            return;
        }

        foreach ($refreshTokens as $refreshToken) {
            $this->refreshTokenRepository->remove($refreshToken);
        }
    }
}