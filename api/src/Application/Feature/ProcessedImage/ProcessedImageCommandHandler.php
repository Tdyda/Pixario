<?php

namespace App\Application\Feature\ProcessedImage;

use App\Application\Port\FileStorageInterface;
use App\Application\Port\GalleryRepositoryInterface;
use App\Core\Domain\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


final readonly class ProcessedImageCommandHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private FileStorageInterface $fileStorage,
        private ProcessedImageNotificationPublisher $notificationPublisher,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function handle(ProcessedImageCommand $command): ProcessedImageResult
    {
        $gallery = $this->galleryRepository->findById($command->galleryId);


        if ($gallery === null) {
            throw new NotFoundHttpException('Gallery not found.');
        }

        $pathsArray = $this->fileStorage->saveImage(
            $command->files,
            (string)$command->galleryId
        );

        foreach ($command->files as $file) {
            $gallery->addPhoto(
                Image::create(
                    $file->getClientOriginalName(),
                    (string)$command->galleryId
                )
            );
        }

        $this->galleryRepository->update($gallery);
        $this->notificationPublisher->notify($gallery, $command->result);

        return new ProcessedImageResult(
            galleryId: (string)$command->galleryId,
            pathsArray: $pathsArray,
            results: $command->result,
        );
    }
}