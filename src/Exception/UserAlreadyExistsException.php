<?php

namespace App\Exception;


use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserAlreadyExistsException extends UnauthorizedHttpException
{
    public function __construct(string $message = 'Użytkownik z tym adresem email już istnieje!')
    {
        parent::__construct('Basic', $message);
    }
}