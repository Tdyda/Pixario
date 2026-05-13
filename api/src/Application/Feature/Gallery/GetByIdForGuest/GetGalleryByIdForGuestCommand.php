<?php

namespace App\Application\Feature\Gallery\GetByIdForGuest;

class GetGalleryByIdForGuestCommand
{
    public function __construct(
        public string $id,
        public string $emailAddress,
        public string $password
    ) {
    }
}