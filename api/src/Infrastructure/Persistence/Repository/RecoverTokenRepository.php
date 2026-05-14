<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\RecoverTokenRepositoryInterface;
use App\Core\Domain\RecoverToken;
use App\Core\Domain\User;
use App\Infrastructure\Persistence\Mapper\RecoverTokenMapper;
use App\Infrastructure\Persistence\Mapper\UserMapper;
use App\Infrastructure\Persistence\Model\RecoverTokenEntity;
use App\Infrastructure\Persistence\Model\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecoverTokenEntity>
 */
class RecoverTokenRepository extends ServiceEntityRepository implements RecoverTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecoverTokenEntity::class);
    }

    function findByHashToken(string $tokenHash): User
    {
        /** @var RecoverTokenEntity|null $entity */
        $entity = $this->findOneBy(['tokenHash' => $tokenHash]);
        if (!$entity) {
            throw new \RuntimeException('Token not found');
        }
        $user = $entity->getUserRef();

        return UserMapper::toDomain($user);
    }

    /**
     * @throws ORMException
     */
    function save(RecoverToken $recoverToken, User $user): void
    {
        $userEntity = $this->getEntityManager()->getReference(
            UserEntity::class,
            $user->getId()
        );

        $entity = RecoverTokenMapper::toEntity($recoverToken, $userEntity);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
