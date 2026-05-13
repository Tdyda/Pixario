<?php

namespace App\Application\Feature\ProcessedImage;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProcessedImageCommand
{
    /**
     * @param UploadedFile[] $files
     */
    public function __construct(
        public array $files,
        public UuidInterface $galleryId,
        public array $result
    ) {
    }
}