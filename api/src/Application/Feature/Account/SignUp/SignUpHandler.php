<?php

namespace App\Application\Feature\Account\SignUp;

use App\Api\Http\Exception\UserAlreadyExistsException;
use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\User;
use Ramsey\Uuid\Uuid;

final readonly class SignUpHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(SignUpCommand $command): void
    {
        if ($this->userRepository->findOneBy(['email' => $command->email])) {
            throw new UserAlreadyExistsException();
        }

        $uuid = Uuid::uuid4()->toString();
        $user = User::create($uuid, $command->email, ['ROLE_USER']);

        $this->userRepository->save($user, $command->plainPassword);
    }
}