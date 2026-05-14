<?php

namespace App\Application\Feature\Account\SignUp;

use App\Api\Http\Exception\UserAlreadyExistsException;
use App\Application\Port\MailerPort;
use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\User;
use Ramsey\Uuid\Uuid;
use Random\RandomException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final readonly class SignUpHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailerPort $mailer,
        private ActivationMailBuilder $activationMailBuilder,
    ) {
    }

    /**
     * @throws RandomException
     */
    public function handle(SignUpCommand $command): void
    {
        if ($this->userRepository->findOneBy(['email' => $command->email])) {
            throw new UserAlreadyExistsException();
        }

        $uuid = Uuid::uuid4()->toString();
        $activationToken = bin2hex(random_bytes(32));

        $user = User::create($uuid, $command->email, $activationToken, ['ROLE_USER']);

        $this->userRepository->save($user, $command->plainPassword);

        $activationUrl = sprintf(
            'https://pixario.pl/activate-account?token=%s',
            $activationToken
        );

        $this->mailer->send(
            $command->email,
            $this->activationMailBuilder->buildSubject(),
            $this->activationMailBuilder->buildHtml($activationUrl)
        );
    }
}