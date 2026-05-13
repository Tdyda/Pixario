<?php

namespace App\Application\Feature\Gallery\Delete;

final readonly class DeleteGalleryByIdCommand
{
    public function __construct(
        public string $galleryId
    ) {
    }
}