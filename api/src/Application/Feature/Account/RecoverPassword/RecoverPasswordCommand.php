<?php

namespace App\Application\Feature\Account\RecoverPassword;

final readonly class RecoverPasswordCommand
{
    public function __construct(
        public string $recoverToken,
        public string $newPassword
    ) {
    }
}