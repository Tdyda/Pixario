<?php

namespace App\Application\Feature\UploadImage;

final readonly class UploadImagesCommand
{
    public function __construct(
        public string $galleryId,
        public array $images
    ) {
    }
}