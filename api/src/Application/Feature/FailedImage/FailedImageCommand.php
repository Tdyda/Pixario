<?php

namespace App\Application\Feature\FailedImage;

use Ramsey\Uuid\UuidInterface;

final readonly class FailedImageCommand
{
    public function __construct(
        public UuidInterface $galleryId,
        public array $result
    ) {
    }
}