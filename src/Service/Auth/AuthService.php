<?php

namespace App\Service\Auth;

use App\DTO\Auth\SignInRequest;
use App\DTO\Auth\SignUpRequest;
use App\DTO\Auth\TokenPairDto;
use App\Entity\User;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAlreadyExistsException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Service\Token\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class AuthService
{
    public function __construct(
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JwtService                  $jwtService,
        private readonly EntityManagerInterface      $em,
        private readonly UserFactory                 $factory,
        private readonly RefreshTokenManager         $refreshTokenManager,
    )
    {
    }

    public function signIn(SignInRequest $dto): TokenPairDto
    {
        $user = $this->getUserCredentialsAreValid($dto->email, $dto->password);

        $accessToken = $this->jwtService->createAccessToken($user);
        $refreshToken = $this->refreshTokenManager->createAndPersistRefreshToken($user);

        return new TokenPairDto($accessToken, $refreshToken);
    }

    public function signUp(SignUpRequest $dto): void
    {
        if ($this->userRepository->findOneBy(['email' => $dto->email])) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->factory->createUser($dto);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function refreshAccessToken(string $refreshToken): string
    {
        return $this->refreshTokenManager->refreshAccessToken($refreshToken);
    }

    private function getUserCredentialsAreValid(string $email, string $plainPassword): User
    {
        $user = $this->getUserOrFail($email);

        if (!$this->passwordHasher->isPasswordValid($user, $plainPassword)) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUserOrFail(string $email): User
    {
        return $this->userRepository->findOneBy(['email' => $email]) ?? throw new UserNotFoundException();
    }

}