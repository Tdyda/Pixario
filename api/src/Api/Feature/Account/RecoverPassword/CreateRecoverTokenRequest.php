<?php

namespace App\Api\Feature\Account\RecoverPassword;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class CreateRecoverTokenRequest
{
    public function __construct(
        #[Assert\Email]
        public string $email,
    ) {
    }
}