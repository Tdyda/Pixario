<?php

namespace App\Application\Feature\Gallery\GetGalleryByIdForUser;

class GetGalleryByIdForUserQuery
{
    public function __construct(
        public string $id,
        public ?string $userId = null
    ) {
    }
}