<?php

namespace App\Service\Validation;

use Symfony\Component\Validator\Constraints as Assert;
class SignUpRequest extends SignInRequest
{
    #[Assert\NotBlank(message: "Pole powtórz hasło nie może być puste.")]
    public string $retypedPassword = '';

    #[Assert\Expression(
        expression: "this.password === this.retypedPassword",
        message: "Hasła muszą być identyczne."
    )]
    public function isPasswordMatching(): bool
    {
        return $this->password === $this->retypedPassword;
    }
}