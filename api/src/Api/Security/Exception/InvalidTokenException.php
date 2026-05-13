<?php

namespace App\Api\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidTokenException extends AuthenticationException
{
    public function __construct(string $message = 'JWT token is invalid or missing')
    {
        parent::__construct($message);
    }
}