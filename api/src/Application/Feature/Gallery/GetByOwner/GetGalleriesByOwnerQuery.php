<?php

namespace App\Application\Feature\Gallery\GetByOwner;

final readonly class GetGalleriesByOwnerQuery
{
    public function __construct(
        public string $ownerId
    ) {
    }
}