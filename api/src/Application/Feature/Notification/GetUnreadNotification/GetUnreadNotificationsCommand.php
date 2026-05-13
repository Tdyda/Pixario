<?php

namespace App\Application\Feature\Notification\GetUnreadNotification;

final readonly class GetUnreadNotificationsCommand
{
    public function __construct(
        public string $username
    ) {
    }
}