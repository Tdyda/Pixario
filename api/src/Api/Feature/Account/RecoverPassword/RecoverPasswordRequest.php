<?php

namespace App\Api\Feature\Account\RecoverPassword;

use Symfony\Component\Validator\Constraints as Assert;
class RecoverPasswordRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex('/^[a-f0-9]{64}$/')]
        public string $recoverToken,

        #[Assert\NotBlank(message: "Hasło nie może być puste.")]
        #[Assert\Length(
            min: 8,
            minMessage: "Hasło musi mieć co najmniej {{ limit }} znaków."
        )]
        #[Assert\Regex(
            pattern: '/[A-Z]/',
            message: "Hasło musi zawierać co najmniej jedną wielką literę."
        )]
        #[Assert\Regex(
            pattern: '/[\W]/',
            message: "Hasło musi zawierać co najmniej jeden znak specjalny."
        )]
        public string $newPassword
    ) {
    }
}