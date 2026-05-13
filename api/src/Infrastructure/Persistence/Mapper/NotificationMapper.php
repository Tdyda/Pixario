<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\Notification;
use App\Infrastructure\Persistence\Model\NotificationEntity;

final readonly class NotificationMapper
{
    public static function toDomain(NotificationEntity $notification): Notification
    {
        return Notification::create(
            $notification->getId(),
            $notification->getUserId(),
            $notification->getType(),
            $notification->getMessage(),
            $notification->getGalleryId(),
            $notification->isRead(),
            $notification->getCreatedAt()
        );
    }

    public static function toEntity(Notification $notification): NotificationEntity
    {
        return new NotificationEntity(
            $notification->getUserId(),
            $notification->getType(),
            $notification->getMessage(),
            $notification->getGalleryId()
        );
    }
}