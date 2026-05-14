<?php

namespace App\Core\Domain;

final class User
{
    private function __construct(
        private readonly string $id,
        private readonly string $email,
        private readonly string $activationToken,
        private readonly array $roles,
        private readonly array $refreshTokens,
        private bool $isActive
    ) {
    }

    public static function create(
        string $id,
        string $email,
        string $activationToken,
        array $roles = [],
        array $refreshTokens = [],
        bool $isActive = false
    ): self {
        return new self($id, $email, $activationToken, $roles, $refreshTokens, $isActive);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_values(array_unique($roles));
    }

    public function getRefreshTokens(): array
    {
        return $this->refreshTokens;
    }
}