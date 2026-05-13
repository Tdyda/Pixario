<?php

namespace App\Application\Feature\Notification\GetUnreadNotification;

use App\Application\Port\NotificationRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;

final readonly class GetUnreadNotificationsCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private NotificationRepositoryInterface $notificationRepository
    ) {
    }

    public function handle(GetUnreadNotificationsCommand $command): array
    {
        $user = $this->userRepository->findByEmail($command->username);
        return $this->notificationRepository->getAllUnreadNotificationsForUser($user->getId());
    }
}