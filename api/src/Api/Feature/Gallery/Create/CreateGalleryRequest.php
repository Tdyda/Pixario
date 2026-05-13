<?php

namespace App\Api\Feature\Gallery\Create;

class CreateGalleryRequest
{
    public function __construct(
        public string $name,
        public string $emailAddress,
        public string $password,
        public string $ownerId,
    ) {
    }
}