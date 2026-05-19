<?php

namespace App\Api\Security\Jwt;

use App\Infrastructure\Security\User\AuthenticatedUser;
use Firebase\JWT\JWT;

class AnonymousJwtStrategy implements JwtStrategyInterface
{
    public function __construct(
        private readonly string $jwtSecret,
        private readonly int $accessTtl
    ) {
    }


    public function supports(?AuthenticatedUser $user): bool
    {
        return $user === null;
    }

    public function generateToken(?AuthenticatedUser $user = null, ?string $galleryId = null): string
    {
        if (!$galleryId) {
            throw new \LogicException('AnonymousUser wymaga powiązania z galleryId.');
        }

        $payload = [
            'type' => 'anonymous',
            'iat' => time(),
            'exp' => time() + $this->accessTtl,
            'galleryId' => $galleryId,
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}