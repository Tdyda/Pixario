<?php

namespace App\DTO\Auth;

use App\Entity\RefreshToken;
use Symfony\Component\Validator\Constraints as Assert;
class RefreshRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $refreshToken;

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}