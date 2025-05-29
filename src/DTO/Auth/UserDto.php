<?php

namespace App\DTO\Auth;

use App\Entity\User;

class UserDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly array  $roles
    )
    {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            roles: $user->getRoles()
        );
    }

}

