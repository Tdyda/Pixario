<?php

namespace App\Api\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidApiKeyException extends AuthenticationException
{
    public function __construct(string $message = 'API key is invalid or missing')
    {
        parent::__construct($message);
    }
}