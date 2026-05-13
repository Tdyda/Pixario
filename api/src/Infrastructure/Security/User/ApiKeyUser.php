<?php

namespace App\Infrastructure\Security\User;


use Symfony\Component\Security\Core\User\UserInterface;

class ApiKeyUser implements UserInterface
{
    public function getUserIdentifier(): string
    {
        return 'api-key-user';
    }

    public function getRoles(): array
    {
        return ['ROLE_API_KEY'];
    }

    public function eraseCredentials(): void
    {
    }
}