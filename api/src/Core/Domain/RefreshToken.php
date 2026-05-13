<?php

namespace App\Core\Domain;

final readonly class RefreshToken
{
    private function __construct(
        private string $id,
        private string $token,
        private string $userId,
        private \DateTimeImmutable $expiresAt,
    ) {
    }

    public static function create(
        string $id,
        string $token,
        string $userId,
        \DateTimeImmutable $expiresAt,
    ): self {
        return new self($id, $token, $userId, $expiresAt);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }
}