<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function createUser($dto): User
    {
        $user = new User($dto->email, '');
        $hashed = $this->passwordHasher->hashPassword($user, $dto->password);
        return $user->setPassword($hashed);
    }
}