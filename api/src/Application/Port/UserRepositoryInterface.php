<?php

namespace App\Application\Port;

use App\Core\Domain\User;
use App\Infrastructure\Persistence\Model\UserEntity;

interface UserRepositoryInterface
{
    function findById(string $id): ?User;

    function findByEmailWithRefreshTokens(string $email): ?User;

    function findByEmail(string $email): ?User;

    function findByActivationToken(string $activationToken): ?User;

    function save(User $user, string $plainPassword): void;

    function remove(UserEntity $user): void;

    function update(User $user): void;
}