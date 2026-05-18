<?php

namespace App\Api\Security\Mercure;

use Firebase\JWT\JWT;

final readonly class MercureTokenFactory
{
    public function __construct(
        private string $mercureJwtSecret,
        private int $ttlSeconds = 3600,
    ) {
    }

    public function createSubscriberToken(string $userId): string
    {
        return JWT::encode([
            'mercure' => [
                'subscribe' => [
                    "/users/$userId/notifications",
                ],
            ],
            'exp' => time() + $this->ttlSeconds,
        ], $this->mercureJwtSecret, 'HS256');
    }
}