<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ForbiddenActionException extends AccessDeniedException
{
    public function __construct(string $message = 'Nieautoryzowany dostęp')
    {
        parent::__construct($message);
    }
}