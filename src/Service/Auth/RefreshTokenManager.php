<?php

namespace App\Service\Auth;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Exception\InvalidTokenException;
use App\Repository\RefreshTokenRepository;
use App\Repository\UserRepository;
use App\Service\Token\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class RefreshTokenManager
{
    public function __construct(
        private readonly JwtService             $jwtService,
        private readonly EntityManagerInterface $em,
        private readonly UserRepository         $userRepository,
        private readonly RefreshTokenRepository $refreshTokenRepository,
    )
    {
    }

    public
    function createAndPersistRefreshToken(User $user): string
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

    public function revokeSingleToken(string $token): void
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy(['token' => $token]);

        if($refreshToken) {
            $this->em->remove($refreshToken);
            $this->em->flush();
        }
    }

    public function revokeAllTokensFromAccessToken(string $accessToken): void
    {
        $decoded = $this->jwtService->decode($accessToken);
        $email = $decoded['username'] ?? null;

        if (!$decoded || !$email) {
            throw new InvalidTokenException();
        }

        $user = $this->userRepository->findOneBy(['email' => $email])
            ?? throw new UserNotFoundException();

        $this->revokeAllTokensForUser($user);
    }

    public function revokeAllTokensForUser(User $user): void
    {
        $refreshTokens = $user->getRefreshTokens();

        if ($refreshTokens->isEmpty()) {
            return;
        }

        foreach ($refreshTokens as $refreshToken) {
            $this->em->remove($refreshToken);
        }

        $this->em->flush();
    }

    public function refreshAccessToken(string $refreshToken): string
    {
        $tokenEntity = $this->refreshTokenRepository->findOneBy(['token' => $refreshToken]);

        $now = new \DateTimeImmutable();
        if (!$tokenEntity || $tokenEntity->getExpiresAt() < $now) {
            throw new InvalidTokenException("Refresh token jest nieprawidłowy lub wygasł!");
        }

        $user = $tokenEntity->getUserRef();

        return $this->jwtService->createAccessToken($user);
    }
}