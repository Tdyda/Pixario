<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\AuthUserProviderInterface;
use App\Infrastructure\Persistence\Model\UserEntity;
use App\Infrastructure\Security\User\AuthenticatedUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class AuthUserProvider extends ServiceEntityRepository implements AuthUserProviderInterface
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, UserEntity::class);
    }

    public function findByEmail(string $email): ?AuthenticatedUser
    {
        $entity = $this->findOneBy(['email' => $email]);

        if (!$entity) {
            return null;
        }

        return new AuthenticatedUser(
            $entity->getId(),
            $entity->getEmail(),
            $entity->getRoles(),
            $entity->getPassword()
        );
    }

    public function findByUserId(string $id): ?AuthenticatedUser
    {
        $entity = $this->find($id);

        return new AuthenticatedUser(
            $entity->getId(),
            $entity->getEmail(),
            $entity->getRoles(),
            $entity->getPassword()
        );
    }
}