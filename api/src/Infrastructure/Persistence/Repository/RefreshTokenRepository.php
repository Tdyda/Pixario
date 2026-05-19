<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\RefreshTokenRepositoryInterface;
use App\Core\Domain\RefreshToken;
use App\Infrastructure\Persistence\Mapper\RefreshTokenMapper;
use App\Infrastructure\Persistence\Model\RefreshTokenEntity;
use App\Infrastructure\Persistence\Model\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Exception\LogicException;

/**
 * @extends ServiceEntityRepository<RefreshTokenEntity>
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshTokenEntity::class);
    }

    /**
     * @throws ORMException
     */
    function save(RefreshToken $refreshToken): void
    {
        $userRef = $this->getEntityManager()->getReference(
            UserEntity::class,
            $refreshToken->getUserId()
        );

        $entity = RefreshTokenMapper::toEntity($refreshToken, $userRef);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    function revoke(RefreshToken $refreshToken): void
    {
        /** @var RefreshTokenEntity $entity */
        $entity = $this->find($refreshToken->getId());
        if (!$entity) {
            throw new LogicException('Refresh token not found');
        }
        $entity->revoke();
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     */
    function remove(RefreshToken $refreshToken): void
    {
        $entity = $this->find($refreshToken->getId());

        if ($entity === null) {
            return;
        }

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    function findByToken(string $token): ?RefreshToken
    {
        return RefreshTokenMapper::toDomain(
            $this->findOneBy(['token' => $token])
        );
    }

}
