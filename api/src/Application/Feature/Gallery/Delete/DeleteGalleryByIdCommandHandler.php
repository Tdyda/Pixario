<?php

namespace App\Application\Feature\Gallery\Delete;

use App\Application\Port\FileStorageInterface;
use App\Application\Port\GalleryRepositoryInterface;

final readonly class DeleteGalleryByIdCommandHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private FileStorageInterface $fileStorage,
    ) {
    }

    public function handle(DeleteGalleryByIdCommand $command): void
    {
        $this->galleryRepository->deleteById($command->galleryId);
        $this->fileStorage->deleteDirectory($command->galleryId);
    }
}