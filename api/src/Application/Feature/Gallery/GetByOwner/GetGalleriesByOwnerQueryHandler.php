<?php

namespace App\Application\Feature\Gallery\GetByOwner;

use App\Application\Feature\Gallery\GalleryResponse;
use App\Application\Port\GalleryRepositoryInterface;
use App\Core\Domain\Gallery;

final readonly class GetGalleriesByOwnerQueryHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
    ) {
    }

    public function handle(GetGalleriesByOwnerQuery $query): array
    {
        $result = $this->galleryRepository->findByOwnerId($query->ownerId);

        return array_map(function (Gallery $gallery): GalleryResponse {
            return new GalleryResponse(
                $gallery->getId(),
                $gallery->getName(),
                $gallery->getEmailAddress(),
                $gallery->getGalleryOwner(),
                null,
                $gallery->getCreatedAt(),
            );
        }, $result);
    }
}

