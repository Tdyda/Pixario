<?php

namespace App\Api\Feature\Account\UI\RefreshToken;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshRequest
{
    #[Assert\NotBlank(message: "Brak refresh tokena!")]
    public ?string $refreshToken = null;
}