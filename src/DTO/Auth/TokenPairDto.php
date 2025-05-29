<?php

namespace App\DTO\Auth;

use App\Entity\RefreshToken;
use Symfony\Component\Validator\Constraints as Assert;

class TokenPairDto
{
    public function __construct(string $accessToken, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }
    #[Assert\NotBlank]
    public string $accessToken;

    #[Assert\NotBlank]
    public string $refreshToken;
}