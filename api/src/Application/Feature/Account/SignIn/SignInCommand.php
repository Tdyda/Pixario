<?php

namespace App\Application\Feature\Account\SignIn;

use App\Api\Feature\Account\UI\Assert;

class SignInCommand
{
    #[Assert\NotBlank(message: 'Email nie może być pusty.')]
    #[Assert\Email(message: 'Niepoprawny adres email')]
    public string $email;

    #[Assert\NotBlank(message: "Hasło nie może być puste.")]
    public string $password = '';
    public bool $rememberMe = false;
}