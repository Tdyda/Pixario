<?php

namespace App\Application\Feature\ProcessedImage;

use App\Api\Feature\Webhook\ResultResponse;

final readonly class ProcessedImageResult
{
    /**
     * @param string[] $pathsArray
     * @param ResultResponse[] $results
     */
    public function __construct(
        public string $galleryId,
        public array $pathsArray,
        public array $results,
    ) {
    }
}