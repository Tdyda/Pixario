<?php

namespace App\Application\Port;

use App\Infrastructure\Security\User\AuthenticatedUser;

interface AuthUserProviderInterface
{
    public function findByEmail(string $email): ?AuthenticatedUser;

    public function findByUserId(string $id): ?AuthenticatedUser;
}