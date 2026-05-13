<?php

namespace App\Application\Port;

use App\Core\Domain\Gallery;

interface GalleryRepositoryInterface
{
    function save(Gallery $gallery): void;

    function update(Gallery $gallery): void;

    function findById(string $id): ?Gallery;

    function findByOwnerId(string $ownerId): array;

    function deleteById(string $galleryId): void;
}