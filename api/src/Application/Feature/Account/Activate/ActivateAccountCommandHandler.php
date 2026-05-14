<?php

namespace App\Application\Feature\Account\Activate;


use App\Application\Port\UserRepositoryInterface;
use LogicException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class ActivateAccountCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(ActivateAccountCommand $command): void
    {
        $user = $this->userRepository->findByActivationToken($command->activationToken);
        if (!$user) {
            throw new UserNotFoundException();
        }

        if($user->isActive() === true)
        {
            throw new LogicException("Account already activated");
        }

        $user->setIsActive(true);
        $this->userRepository->update($user);
    }
}