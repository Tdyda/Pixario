<?php

namespace App\Application\Port;

interface MailerPort
{
    public function send(
        string $to,
        string $subject,
        string $content
    ): void;
}