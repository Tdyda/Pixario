<?php

namespace App\Application\Feature\Gallery\DeleteImageFromGallery;

use App\Application\Port\FileStorageInterface;
use App\Core\Domain\Image;
use App\Infrastructure\Persistence\Repository\GalleryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class DeleteImageFromGalleryCommandHandler
{
    public function __construct(
        private GalleryRepository $galleryRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    public function handle(DeleteImageFromGalleryCommand $command): void
    {
        $gallery = $this->galleryRepository->findById($command->galleryId);
        if ($gallery === null) {
            throw new NotFoundHttpException('Gallery not found');
        }

        $imagesToKeep = array_filter(
            $gallery->getimages(),
            fn(Image $image) => !in_array(
                $image->getId(),
                $command->imageIds,
                true
            )
        );

        $imagesToDelete = array_filter(
            $gallery->getImages(),
            fn(Image $image) => in_array(
                $image->getId(),
                $command->imageIds,
                true
            )
        );

        $gallery->setImages($imagesToKeep);
        $this->galleryRepository->update($gallery);

        foreach ($imagesToDelete as $image) {
            $this->fileStorage->deleteFile($image->getName(), $command->galleryId);
        }
    }
}