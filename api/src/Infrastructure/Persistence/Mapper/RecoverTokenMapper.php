<?php

namespace App\Infrastructure\Persistence\Mapper;

use App\Core\Domain\RecoverToken;
use App\Infrastructure\Persistence\Model\RecoverTokenEntity;
use App\Infrastructure\Persistence\Model\UserEntity;

final readonly class RecoverTokenMapper
{
    public static function toDomain(RecoverTokenEntity $entity): RecoverToken
    {
        $userRef = $entity->getUserRef();

        return RecoverToken::create(
            $userRef->getId(),
            $entity->getTokenHash(),
            $entity->getExpiresAt(),
            $entity->getCreatedAt()        );
    }

    public static function toEntity(RecoverToken $domain, UserEntity $user): RecoverTokenEntity
    {
        return new RecoverTokenEntity(
            $user,
            $domain->getTokenHash(),
            $domain->getExpiresAt(),
            $domain->getCreatedAt()
        );
    }
}