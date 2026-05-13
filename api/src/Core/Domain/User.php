<?php

namespace App\Core\Domain;

final readonly class User
{
    private function __construct(
        private string $id,
        private string $email,
        private array $roles,
        private array $refreshTokens,
    ) {
    }

    public static function create(
        string $id,
        string $email,
        array $roles = [],
        array $refreshTokens = []
    ): self {
        return new self($id, $email, $roles, $refreshTokens);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
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