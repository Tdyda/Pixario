<?php

namespace App\Core\Domain;

final class RecoverToken
{
    private function __construct(
        private readonly string $userId,
        private readonly string $tokenHash,
        private readonly \DateTimeImmutable $expiresAt,
        private readonly \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $usedAt = null
    ) {
    }

    public static function create(string $userId, string $tokenHash, \DateTimeImmutable $expiresAt, \DateTimeImmutable $createdAt): self
    {
        return new self($userId, $tokenHash, $expiresAt, $createdAt);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTokenHash(): string
    {
        return $this->tokenHash;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUsedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function setUsedAt(\DateTimeImmutable $usedAt): void
    {
        $this->usedAt = $usedAt;
    }
}