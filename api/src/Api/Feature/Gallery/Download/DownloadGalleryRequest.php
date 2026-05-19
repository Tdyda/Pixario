<?php

namespace App\Api\Feature\Gallery\Download;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DownloadGalleryRequest
{
    public function __construct(
        #[Assert\NotBlank]
        private string $accessToken,

        #[Assert\NotBlank]
        private string $dir,
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getDir(): string
    {
        return $this->dir;
    }
}