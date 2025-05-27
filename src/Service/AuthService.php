<?php

namespace App\Service;

use App\DTO\Auth\RefreshRequest;
use App\DTO\Auth\SignInRequest;
use App\DTO\Auth\SignUpRequest;
use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAlreadyExistsException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class AuthService
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JwtService                  $jwtService,
        private EntityManagerInterface      $em,
        private UserFactory                 $factory
    )
    {
    }

    public function signIn(SignInRequest $dto)
    {
        $user = $this->getUserCredentialsAreValid($dto->email, $dto->password);

        $accessToken = $this->jwtService->createAccessToken($user);
        $refreshToken = $this->createAndPersistRefreshToken($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    public function signUp(SignUpRequest $dto): void
    {
        $userExists = $this->userExistsByEmail($dto->email);

        if ($userExists) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->factory->createUser($dto);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function refresh(RefreshRequest $dto): string
    {
        $user = $this->userRepository->findOneBy(['email' => $dto->email]);
        $refreshToken = $dto->refreshToken;

        if (!$user || !$refreshToken) {
            throw new UserNotFoundException("Użytkownik nie istnieje");
        }

        return $this->jwtService->createAccessToken($user);
    }

    private function userExistsByEmail(string $email): bool
    {
        return (bool)$this->getUserByEmail($email);
    }

    private function getUserCredentialsAreValid(string $email, string $plainPassword): User
    {
        $user = $this->getUserByEmail($email);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $plainPassword)) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    private function createAndPersistRefreshToken(User $user): string
    {
        $token = $this->jwtService->generateRefreshToken();
        $refreshToken = new RefreshToken();
        $refreshToken->setToken($token);
        $refreshToken->setExpiresAt($this->jwtService->getTokenExpiry('refresh'));
        $refreshToken->setUserRef($user);

        $this->em->persist($refreshToken);
        $this->em->flush();

        return $token;
    }

    private function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}