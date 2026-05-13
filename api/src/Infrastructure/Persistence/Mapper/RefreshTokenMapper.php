<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\RefreshToken;
use App\Infrastructure\Persistence\Model\RefreshTokenEntity;
use App\Infrastructure\Persistence\Model\UserEntity;
use LogicException;

final class RefreshTokenMapper
{
    public static function toDomain(RefreshTokenEntity $entity): RefreshToken
    {
        $userRef = $entity->getUserRef();

        if ($userRef === null) {
            throw new LogicException('RefreshTokenEntity has no assigned user.');
        }

        return RefreshToken::create(
            id: $entity->getId(),
            token: $entity->getToken(),
            userId: $userRef->getId(),
            expiresAt: $entity->getExpiresAt(),
        );
    }

    public static function toEntity(
        RefreshToken $domain,
        UserEntity $user,
    ): RefreshTokenEntity {
        $entity = new RefreshTokenEntity();

        $entity->setId($domain->getId());
        $entity->setToken($domain->getToken());
        $entity->setExpiresAt($domain->getExpiresAt());
        $entity->setUserRef($user);

        return $entity;
    }
}