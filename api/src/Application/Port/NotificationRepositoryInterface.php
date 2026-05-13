<?php

namespace App\Application\Port;

use App\Core\Domain\Notification;

interface NotificationRepositoryInterface
{
    function save(Notification $notification): void;

    function getAllUnreadNotificationsForUser(string $userId): array;

    function markAsRead(string $userId): void;
}