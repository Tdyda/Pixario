<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\Image;
use App\Infrastructure\Persistence\Model\GalleryEntity;
use App\Infrastructure\Persistence\Model\ImageEntity;
use LogicException;

final readonly class ImageMapper
{
    public static function toEntity(Image $domain, GalleryEntity $entity): ImageEntity
    {
        return new ImageEntity(
            $domain->getName(),
            $entity,
            $domain->getUploadedAt()
        );
    }

    public static function toDomain(ImageEntity $entity): Image
    {
        $galleryRef = $entity->getGalleryRef();

        if ($galleryRef === null) {
            throw new LogicException('GalleryEntity has no assigned user.');
        }

        return Image::restore(
            $entity->getId(),
            $entity->getName(),
            $galleryRef->getId(),
        );
    }
}