<?php

namespace App\Application\Feature\Account\RecoverPassword;

use App\Application\Port\RecoverTokenRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class RecoverPasswordCommandHandler
{
    public function __construct(
        private RecoverTokenRepositoryInterface $recoverTokenRepository,
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function handle(RecoverPasswordCommand $command): void
    {
        $hashToken = hash('sha512', $command->recoverToken);

        $user = $this->recoverTokenRepository->findByHashToken($hashToken);

        $this->userRepository->changePassword($user, $command->newPassword);
    }
}