<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidTokenException extends AuthenticationException
{
    public function __construct(string $message = 'Token JWT jest nieprawidłowy lub wygasł')
    {
        parent::__construct($message);
    }
}