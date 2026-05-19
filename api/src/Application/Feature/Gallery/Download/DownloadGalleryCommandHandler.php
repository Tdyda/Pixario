<?php

namespace App\Application\Feature\Gallery\Download;

use App\Api\Http\Exception\InvalidCredentialsException;
use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Port\FileStorageInterface;
use App\Application\Port\GalleryRepositoryInterface;

final class DownloadGalleryCommandHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private jwtService $jwtService,
        private FileStorageInterface $fileStorage
    ) {
    }

    public function handle(DownloadGalleryCommand $command): string
    {
        $gallery = $this->galleryRepository->findById($command->galleryId);

        $payload = $this->jwtService->decode($command->accessToken);

        $userId = $payload['sub'] ?? null;
        $tokenGalleryId = $payload['galleryId'] ?? null;

        if ($userId === null && $tokenGalleryId === null) {
            throw new InvalidTokenException();
        }

        $isOwner = $userId === $gallery->getGalleryOwner();

        $isAnonymousGalleryAccess = $tokenGalleryId !== null
            && $tokenGalleryId === $command->galleryId;

        if (!$isOwner && !$isAnonymousGalleryAccess) {
            throw new InvalidCredentialsException();
        }

        $images = $this->fileStorage->getImages($command->galleryId);

        if ($images === []) {
            throw new \RuntimeException('No images found');
        }

        $zipPath = sys_get_temp_dir() . '/gallery_' . $command->galleryId . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create zip file');
        }

        foreach ($images as $imagePath) {
            $zip->addFile($imagePath, basename($imagePath));
        }

        $zip->close();

        return $zipPath;
    }
}