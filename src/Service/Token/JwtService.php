<?php

namespace App\Service\Token;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    private int $accessTtl;
    private int $refreshTtl;

    public function __construct(string $jwtSecret)
    {
        $this->secret = $jwtSecret;
        $this->accessTtl = 900; // 15 min
        $this->refreshTtl = 2592000; // 30 dni
    }

    public function createAccessToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'username' => $user->getEmail(),
            'exp' => time() + $this->accessTtl
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $token): ?array
    {
        try {
            return (array)JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function generateRefreshToken(): string
    {
        return bin2hex(random_bytes(64));
    }

    public function getTokenExpiry(string $type): \DateTimeImmutable
    {
        return match ($type) {
            'access' => new \DateTimeImmutable('+' . $this->accessTtl . ' seconds'),
            'refresh' => new \DateTimeImmutable('+' . $this->refreshTtl . ' seconds'),
            default => throw new \InvalidArgumentException("Unknown token type: $type"),
        };
    }

}