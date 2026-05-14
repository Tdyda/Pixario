<?php

namespace App\Api\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
class AccountNotActiveException extends AuthenticationException
{
    public function __construct(string $message = 'Account is not active.')
    {
        parent::__construct($message);
    }
}