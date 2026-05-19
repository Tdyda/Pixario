<?php

namespace App\Application\Feature\Gallery\Download;

final readonly class DownloadGalleryCommand
{
    public function __construct(
        public string $accessToken,
        public string $galleryId,
    ) {
    }
}