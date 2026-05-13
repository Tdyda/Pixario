<?php

namespace App\Api\Feature;

final readonly class ImageResponse
{
    public function __construct(
        public string $id,
        public string $fileName,
        public string $name,
        public \DateTimeImmutable $uploadedAt,
    ) {
    }
}