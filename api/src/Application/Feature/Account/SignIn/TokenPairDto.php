<?php

namespace App\Application\Feature\Account\SignIn;

use Symfony\Component\Validator\Constraints as Assert;

class TokenPairDto
{
    #[Assert\NotBlank]
    public string $accessToken;
    #[Assert\NotBlank]
    public string $refreshToken;

    public function __construct(string $accessToken, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }
}