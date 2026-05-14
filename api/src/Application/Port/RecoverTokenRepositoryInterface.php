<?php

namespace App\Application\Port;

use App\Core\Domain\RecoverToken;
use App\Core\Domain\User;

interface RecoverTokenRepositoryInterface
{
    function findByHashToken(string $tokenHash): User;
    function save(RecoverToken $recoverToken, User $user): void;
}