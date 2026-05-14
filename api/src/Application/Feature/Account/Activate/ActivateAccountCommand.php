<?php

namespace App\Application\Feature\Account\Activate;

final readonly class ActivateAccountCommand
{
    public function __construct(
        public string $activationToken
    ) {
    }
}