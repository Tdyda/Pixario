<?php

namespace App\Application\Feature\Gallery\Create;

final class CreateGalleryCommand
{
    public function __construct(
        public string $name,
        public string $emailAddress,
        public string $password,
        public string $galleryOwner,
    ) {
    }
}