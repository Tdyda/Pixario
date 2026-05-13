<?php

namespace App\Application\Feature\Gallery\DeleteImageFromGallery;

final readonly class DeleteImageFromGalleryCommand
{
    /**
     * @param int[] $imageIds
     */
    public function __construct(
        public string $galleryId,
        public array $imageIds
    ) {
    }
}