<?php

namespace App\Application\Feature\Notification\MarkAsReadNotification;

final readonly class MarkAsReadNotificationCommand
{
    public function __construct(
        public string $username
    ) {
    }
}