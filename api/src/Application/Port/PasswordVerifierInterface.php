<?php

namespace App\Application\Port;

interface PasswordVerifierInterface
{
    public function verify(string $passwordHash, string $plainPassword): bool;
}