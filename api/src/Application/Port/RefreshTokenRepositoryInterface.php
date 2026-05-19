<?php

namespace App\Application\Port;

use App\Core\Domain\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    function findByToken(string $token): ?RefreshToken;

    function save(RefreshToken $refreshToken): void;

    function remove(RefreshToken $refreshToken): void;

    function revoke(RefreshToken $refreshToken): void;
}