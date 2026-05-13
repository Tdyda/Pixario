<?php

namespace App\Application\Feature\SendEmail;

use App\Application\Port\MailerPort;

final readonly class SendEmailHandler
{
    public function __construct(
        private MailerPort $mailer
    ) {
    }

    public function handle(string $emailAddress): void
    {
        $this->mailer->send(
            $emailAddress,
            'Welcome',
            '<h1>Hello!</h1>'
        );
    }
}