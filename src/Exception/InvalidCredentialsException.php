<?php

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InvalidCredentialsException extends UnauthorizedHttpException
{
    public function __construct(string $message = 'Błędne dane logowania')
    {
        parent::__construct('Basic', $message);
    }
}