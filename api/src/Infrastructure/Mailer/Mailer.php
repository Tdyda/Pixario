<?php

namespace App\Infrastructure\Mailer;

use App\Application\Port\MailerPort;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class Mailer implements MailerPort
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(
        string $to,
        string $subject,
        string $content
    ): void {
        $email = (new Email())
            ->from('noreply@doublecodestudio.pl')
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}