<?php

namespace App\Infrastructure\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class ApplicationUser implements UserInterface
{
    public function __construct(
        private string $id,
        private string $email,
        private array $roles,
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
        return array_values(array_unique($this->roles));
    }

    public function eraseCredentials(): void
    {
    }
}