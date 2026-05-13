<?php

namespace App\Api\Feature\Gallery\Delete;

final readonly class DeleteGalleryRequest
{
    public function __construct(
        public string $galleryId
    ) {
    }
}