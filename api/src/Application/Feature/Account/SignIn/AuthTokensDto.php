<?php

namespace App\Application\Feature\Account\SignIn;

use Symfony\Component\Validator\Constraints as Assert;

class AuthTokensDto
{
    #[Assert\NotBlank]
    public string $accessToken;
    #[Assert\NotBlank]
    public string $refreshToken;
    #[Assert\NotBlank]
    public string $mercureToken;

    public function __construct(string $accessToken, string $refreshToken, string $mercureToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->mercureToken = $mercureToken;
    }
}