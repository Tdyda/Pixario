<?php

namespace App\Application\Feature\Account\SignIn;

use App\Api\Http\Exception\InvalidCredentialsException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Feature\Account\RefreshToken\Create\CreateRefreshTokenHandler;
use App\Application\Port\AuthUserProviderInterface;
use App\Application\Port\PasswordVerifierInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class SignInHandler
{
    public function __construct(
        private AuthUserProviderInterface $authUserProvider,
        private PasswordVerifierInterface $passwordVerifier,
        private JwtService $jwtService,
        private CreateRefreshTokenHandler $handler
    ) {
    }

    public function handle(SignInCommand $command): TokenPairDto
    {
        $user = $this->authUserProvider->findByEmail($command->email)
            ?? throw new UserNotFoundException();

        if (!$this->passwordVerifier->verify($user->passwordHash, $command->password)) {
            throw new InvalidCredentialsException();
        }

        $accessToken = $this->jwtService->createAccessToken($user);

        $refreshToken = $this->handler->createAndPersistRefreshToken($user->id);

        return new TokenPairDto($accessToken, $refreshToken);
    }
}