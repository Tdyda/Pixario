<?php

namespace App\Api\Feature\Gallery\GetByIdForGuest;

final readonly class GetGalleryByIdForGuestRequest
{
    public function __construct(
        public string $id,
        public string $emailAddress,
        public string $password,
    ) {
    }
}