<?php

namespace App\Api\Security\Jwt;

use App\Infrastructure\Security\User\AuthenticatedUser;

interface JwtStrategyInterface
{
    public function supports(?AuthenticatedUser $user): bool;

    public function generateToken(?AuthenticatedUser $user = null): string;
}