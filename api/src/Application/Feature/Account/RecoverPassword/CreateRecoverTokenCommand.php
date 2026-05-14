<?php

namespace App\Application\Feature\Account\RecoverPassword;

class CreateRecoverTokenCommand
{
    public function __construct(
        public string $email
    ) {
    }
}