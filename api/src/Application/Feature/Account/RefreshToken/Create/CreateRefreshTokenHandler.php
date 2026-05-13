<?php

namespace App\Application\Feature\Account\RefreshToken\Create;

use App\Api\Security\Jwt\JwtService;
use App\Application\Port\RefreshTokenRepositoryInterface;
use App\Core\Domain\RefreshToken;
use Ramsey\Uuid\Uuid;

class CreateRefreshTokenHandler
{
    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository,
        private readonly JwtService $jwtService
    ) {
    }

    public function createAndPersistRefreshToken(string $userId): string
    {
        $id = Uuid::uuid7()->toString();
        $token = $this->jwtService->generateRefreshToken();
        $expiresAt = $this->jwtService->getTokenExpiry('refresh');
        $refreshToken = RefreshToken::create($id, $token, $userId, $expiresAt);

        $this->refreshTokenRepository->save($refreshToken);
        return $token;
    }
}