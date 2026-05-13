<?php

namespace App\Application\Feature\Account\SignUp;

use Symfony\Component\Validator\Constraints as Assert;

class SignUpCommand
{
    #[Assert\NotBlank(message: 'Email nie może być pusty.')]
    #[Assert\Email(message: 'Niepoprawny adres email')]
    public string $email;

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
    public string $plainPassword = '';
    #[Assert\NotBlank(message: "Pole powtórz hasło nie może być puste.")]
    public string $retypedPassword = '';

    #[Assert\Expression(
        expression: "this.plainPassword === this.retypedPassword",
        message: "Hasła muszą być identyczne."
    )]
    public function isPasswordMatching(): bool
    {
        return $this->plainPassword === $this->retypedPassword;
    }
}