<?php

namespace App\Application\Feature\FailedImage;

use App\Application\Port\GalleryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class FailedImageCommandHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private FailedImageNotificationPublisher $notificationPublisher,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function handle(FailedImageCommand $command): void
    {
        $gallery = $this->galleryRepository->findById($command->galleryId);

        if ($gallery === null) {
            throw new NotFoundHttpException('Gallery not found.');
        }

        $this->notificationPublisher->notify($gallery, $command->result);
    }
}