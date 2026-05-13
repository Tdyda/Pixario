<?php

namespace App\Api\Security\Jwt;

use App\Infrastructure\Security\User\AuthenticatedUser;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    private int $accessTtl;
    private int $refreshTtl;
    private iterable $strategies;

    /**
     * @param iterable<JwtStrategyInterface> $strategies
     */
    public function __construct(string $jwtSecret, iterable $strategies)
    {
        $this->secret = $jwtSecret;
        $this->accessTtl = 900; // 15 min
        $this->refreshTtl = 2592000; // 30 dni
        $this->strategies = $strategies;
    }

    public function createAccessToken(?AuthenticatedUser $user = null): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($user)) {
                return $strategy->generateToken($user);
            }
        }
        throw new \RuntimeException('No strategies to generate JWT for this case.');
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