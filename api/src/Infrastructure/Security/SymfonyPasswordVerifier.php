<?php

namespace App\Infrastructure\Security;

use App\Application\Port\PasswordVerifierInterface;
use App\Infrastructure\Persistence\Model\UserEntity;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final readonly class SymfonyPasswordVerifier implements PasswordVerifierInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $factory,
    ) {
    }

    public function verify(string $passwordHash, string $plainPassword): bool
    {
        $hasher = $this->factory->getPasswordHasher(UserEntity::class);

        return $hasher->verify($passwordHash, $plainPassword);
    }
}