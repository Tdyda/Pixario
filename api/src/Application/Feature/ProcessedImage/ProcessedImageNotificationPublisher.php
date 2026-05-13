<?php

namespace App\Application\Feature\ProcessedImage;

use App\Api\Feature\Webhook\ResultResponse;
use App\Application\Port\MailerPort;
use App\Application\Port\NotificationRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\Gallery;
use App\Core\Domain\Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final readonly class ProcessedImageNotificationPublisher
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private NotificationRepositoryInterface $notificationRepository,
        private MailerPort $mailer,
        private ProcessedImageMailBuilder $mailBuilder,
        private HubInterface $hub,
    ) {
    }

    /**
     * @param ResultResponse[] $results
     * @throws \JsonException
     */
    public function notify(Gallery $gallery, array $results): void
    {
        $user = $this->userRepository->findById($gallery->getGalleryOwner());

        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }

        $this->mailer->send(
            $user->getEmail(),
            $this->mailBuilder->buildSubject($gallery->getName(), $results),
            $this->mailBuilder->buildHtml($gallery->getName(), $results)
        );

        $message = sprintf(
            'Zdjęcia zostały dodane do galerii: %s',
            $gallery->getName()
        );

        $notification = Notification::create(
            null,
            userId: $gallery->getGalleryOwner(),
            type: 'photos.processed',
            message: $message,
            galleryId: $gallery->getId()
        );

        $this->notificationRepository->save($notification);

        $this->hub->publish(
            new Update(
                "/users/{$gallery->getGalleryOwner()}/notifications",
                json_encode([
                    'id' => $notification->getId(),
                    'type' => 'photos.processed',
                    'message' => $message,
                    'galleryId' => $gallery->getId(),
                    'read' => false,
                    'createdAt' => $notification->getCreatedAt()->format(DATE_ATOM),
                ], JSON_THROW_ON_ERROR)
            )
        );
    }
}