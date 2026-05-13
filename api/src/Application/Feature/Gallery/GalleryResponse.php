<?php

namespace App\Application\Feature\Gallery;

final readonly class GalleryResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $emailAddress,
        public string $galleryOwner,
        public ?array $images = null,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}