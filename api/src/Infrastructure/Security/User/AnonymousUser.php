<?php

namespace App\Infrastructure\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class AnonymousUser implements UserInterface
{
    public function __construct()
    {
    }

    public function getRoles(): array
    {
        return ['ROLE_ANONYMOUS_JWT'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return 'anonymous';
    }
}