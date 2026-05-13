<?php

namespace App\Api\Feature\UploadImage;

final readonly class UploadImagesResponse
{
    public function __construct(
        public string $jobId,
    ) {
    }
}