<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\RefreshToken;
use App\Core\Domain\User;
use App\Infrastructure\Persistence\Model\RefreshTokenEntity;
use App\Infrastructure\Persistence\Model\UserEntity;
use App\Infrastructure\Security\User\ApplicationUser;

final class UserMapper
{
    public static function toDomain(UserEntity $entity): User
    {
        return User::create(
            id: $entity->getId(),
            email: $entity->getEmail(),
            roles: $entity->getRoles(),
            refreshTokens: array_map(
                static fn(RefreshTokenEntity $token): RefreshToken => RefreshTokenMapper::toDomain($token),
                $entity->getRefreshTokens()->toArray()
            )
        );
    }

    public static function toEntity(User $domain): UserEntity
    {
        $entity = new UserEntity(
            $domain->getId(),
            $domain->getEmail(),
            $domain->getRoles(),
        );

        foreach ($domain->getRefreshTokens() as $refreshToken) {
            $entity->addRefreshToken(
                RefreshTokenMapper::toEntity($refreshToken, $entity)
            );
        }

        return $entity;
    }

    public static function toApplicationUser(UserEntity $entity): ApplicationUser
    {
        return new ApplicationUser(
            id: $entity->getId(),
            email: $entity->getEmail(),
            roles: $entity->getRoles(),
        );
    }

    public static function applicationUserToDomain(ApplicationUser $applicationUser): User
    {
        return User::create(
            $applicationUser->getId(),
            $applicationUser->getUserIdentifier(),
            $applicationUser->getRoles()
        );
    }
}