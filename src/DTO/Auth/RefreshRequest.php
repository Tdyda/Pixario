<?php

namespace App\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshRequest
{
    #[Assert\NotBlank(message: "Brak refresh tokena!")]
    public ?string $refreshToken = null;
}