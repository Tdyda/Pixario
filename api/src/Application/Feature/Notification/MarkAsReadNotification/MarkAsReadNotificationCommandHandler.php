<?php

namespace App\Application\Feature\Notification\MarkAsReadNotification;

use App\Application\Port\NotificationRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;

final readonly class MarkAsReadNotificationCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function handle(MarkAsReadNotificationCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->username);
        $this->notificationRepository->markAsRead($user->getId());
    }
}