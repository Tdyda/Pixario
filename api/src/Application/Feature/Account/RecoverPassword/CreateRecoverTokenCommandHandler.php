<?php

namespace App\Application\Feature\Account\RecoverPassword;

use App\Application\Port\MailerPort;
use App\Application\Port\RecoverTokenRepositoryInterface;
use App\Core\Domain\RecoverToken;
use App\Infrastructure\Persistence\Repository\UserRepository;

final readonly class CreateRecoverTokenCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private RecoverTokenRepositoryInterface $recoverTokenRepository,
        private MailerPort $mailer,
        private RecoverPasswordMailBuilder $recoverPasswordMailBuilder
    )
    {
    }

    public function handle(CreateRecoverTokenCommand $command)
    {
        $user = $this->userRepository->findByEmail($command->email);

        if(!$user) {
            return;
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha512', $token);

        $recoverToken = RecoverToken::create(
            $user->getId(),
            $tokenHash,
            new \DateTimeImmutable('+15 minutes'),
            new \DateTimeImmutable()
        );

        $this->recoverTokenRepository->save($recoverToken, $user);

        $recoverPasswordUrl = sprintf(
            'https://pixario.pl/recover-password?token=%s',
            $token
        );

        $this->mailer->send(
            $command->email,
            $this->recoverPasswordMailBuilder->buildSubject(),
            $this->recoverPasswordMailBuilder->buildHtml($recoverPasswordUrl)
        );
    }
}