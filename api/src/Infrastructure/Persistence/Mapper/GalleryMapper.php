<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\Gallery;
use App\Infrastructure\Persistence\Model\GalleryEntity;
use App\Infrastructure\Persistence\Model\ImageEntity;
use App\Infrastructure\Persistence\Model\UserEntity;

final class GalleryMapper
{
    public static function toEntity(Gallery $gallery, UserEntity $ownerRef): GalleryEntity
    {
        $entity = new GalleryEntity(
            $gallery->getId(),
            $gallery->getName(),
            $gallery->getEmailAddress(),
            $gallery->getPassword(),
            $ownerRef
        );

        foreach ($gallery->getImages() as $photo) {
            $entity->addPhoto(
                ImageMapper::toEntity(
                    $photo,
                    $entity
                )
            );
        }

        return $entity;
    }

    public static function toDomain(GalleryEntity $gallery): Gallery
    {
        $images = array_map(
            fn(ImageEntity $i) => ImageMapper::toDomain($i),
            $gallery->getImages()->toArray()
        );

        $ownerRef = $gallery->getOwnerRef();

        return Gallery::create(
            $gallery->getId(),
            $gallery->getName(),
            $gallery->getEmailAddress(),
            $gallery->getPassword(),
            $ownerRef->getId(),
            $images,
            $gallery->getCreatedAt()
        );
    }
}