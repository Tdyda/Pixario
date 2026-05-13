<?php

namespace App\Api\Security\Jwt;

use App\Infrastructure\Security\User\AuthenticatedUser;
use Firebase\JWT\JWT;

class UserJwtStrategy implements JwtStrategyInterface
{
    public function __construct(
        private readonly string $jwtSecret,
        private readonly int $ttlSeconds
    ) {
    }

    public function supports(?AuthenticatedUser $user): bool
    {
        return $user !== null;
    }

    public function generateToken(?AuthenticatedUser $user = null): string
    {
        if (!$user) {
            throw new \LogicException('UserJwtStrategy wymaga obiektu UserEntity.');
        }

        $payload = [
            'sub' => $user->getId(),
            'username' => $user->getUserIdentifier(),
            'exp' => time() + $this->ttlSeconds
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }
}