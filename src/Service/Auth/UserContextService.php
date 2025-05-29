<?php

namespace App\Service\Auth;

use App\DTO\Auth\UserDto;
use App\Exception\InvalidTokenException;
use App\Repository\UserRepository;
use App\Service\Token\JwtService;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserContextService
{
    public function __construct(
        private readonly JwtService     $jwtService,
        private readonly UserRepository $userRepository,
    )
    {

    }

    /**
     * @throws InvalidTokenException
     * @throws UserNotFoundException
     */
    public function authMe(string $token): UserDto
    {
        $accessToken = $this->jwtService->decode($token);
        $email = $accessToken['username'] ?? null;

        if (!$accessToken || !$email) {
            throw new InvalidTokenException();
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return UserDto::fromEntity($user);
    }
}