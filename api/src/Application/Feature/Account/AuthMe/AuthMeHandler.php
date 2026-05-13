<?php

namespace App\Application\Feature\Account\AuthMe;

use App\Api\Security\Exception\InvalidTokenException;
use App\Api\Security\Jwt\JwtService;
use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class AuthMeHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtService $jwtService
    ) {
    }

    public function handle(string $token): User
    {
        $accessToken = $this->jwtService->decode($token);
        $email = $accessToken['username'] ?? null;

        if (!$accessToken || !$email) {
            throw new InvalidTokenException();
        }

        $user = $this->userRepository->findByEmailWithRefreshTokens($email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}