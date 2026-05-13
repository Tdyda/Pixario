<?php

namespace App\Api\Feature\Gallery\DeleteImageFromGallery;

final readonly class DeleteImageFromGalleryRequest
{
    /**
     * @param int[] $imageIds
     */
    public function __construct(
        public array $imageIds
    ) {
    }
}