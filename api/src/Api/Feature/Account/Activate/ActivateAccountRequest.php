<?php
namespace App\Api\Feature\Account\Activate;

namespace App\Api\Feature\Account\Activate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ActivateAccountRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex('/^[a-f0-9]{64}$/')]
        public string $activationToken
    ) {
    }
}
