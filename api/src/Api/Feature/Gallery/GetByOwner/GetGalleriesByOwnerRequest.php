<?php

namespace App\Api\Feature\Gallery\GetByOwner;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetGalleriesByOwnerRequest
{
    #[Assert\Uuid]
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}