<?php

namespace App\Application\Feature\Account\SignIn;

use App\Api\Http\Exception\InvalidCredentialsException;
use App\Api\Security\Exception\AccountNotActiveException;
use App\Api\Security\Jwt\JwtService;
use App\Api\Security\Mercure\MercureTokenFactory;
use App\Application\Feature\Account\RefreshToken\Create\CreateRefreshTokenHandler;
use App\Application\Port\AuthUserProviderInterface;
use App\Application\Port\PasswordVerifierInterface;
use App\Application\Port\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class SignInHandler
{
    public function __construct(
        private AuthUserProviderInterface $authUserProvider,
        private UserRepositoryInterface $userRepository,
        private PasswordVerifierInterface $passwordVerifier,
        private JwtService $jwtService,
        private CreateRefreshTokenHandler $handler,
        private MercureTokenFactory $mercureTokenFactory,
    ) {
    }

    public function handle(SignInCommand $command): AuthTokensDto
    {
        $authUser = $this->authUserProvider->findByEmail($command->email)
            ?? throw new UserNotFoundException();

        if (!$this->passwordVerifier->verify($authUser->passwordHash, $command->password)) {
            throw new InvalidCredentialsException();
        }

        $user = $this->userRepository->findByEmail($command->email)
            ?? throw new UserNotFoundException();

        if ($user->isActive() === false) {
            throw new AccountNotActiveException();
        }

        $accessToken = $this->jwtService->createAccessToken($authUser);

        $refreshToken = $this->handler->createAndPersistRefreshToken($authUser->id);

        $mercureToken = $this->mercureTokenFactory->createSubscriberToken($authUser->id);

        return new AuthTokensDto($accessToken, $refreshToken, $mercureToken);
    }
}