<?php

namespace App\Infrastructure\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class AuthenticatedUser implements UserInterface
{
    public function __construct(
        public string $id,
        public string $email,
        public array $roles,
        public string $passwordHash,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function eraseCredentials(): void
    {
    }
}