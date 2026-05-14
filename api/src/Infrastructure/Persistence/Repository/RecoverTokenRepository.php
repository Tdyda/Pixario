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
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            throw new NotFoundHttpException('Token not found'); //404
        }
        if($entity->getExpiresAt() <= new \DateTimeImmutable('now')) {
            throw new GoneHttpException('Token expired'); //410
        }
        if($entity->getUsedAt() !== null) {
            throw new ConflictHttpException('Token already used'); //409
        }
        $user = $entity->getUserRef();

        $entity->setUsedAt(new \DateTimeImmutable('now'));
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

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
