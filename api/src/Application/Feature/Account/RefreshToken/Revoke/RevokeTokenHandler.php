<?php

namespace App\Application\Feature\Account\RefreshToken\Revoke;

use App\Application\Port\RefreshTokenRepositoryInterface;

final readonly class RevokeTokenHandler
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
    }

    public function handle(string $token): void
    {
        $refreshToken = $this->refreshTokenRepository->findByToken($token);

        if ($refreshToken) {
            $this->refreshTokenRepository->remove($refreshToken);
        }
    }
}