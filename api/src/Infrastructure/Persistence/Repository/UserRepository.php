<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\User;
use App\Infrastructure\Persistence\Mapper\UserMapper;
use App\Infrastructure\Persistence\Model\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Uid\Exception\LogicException;

/**
 * @extends ServiceEntityRepository<UserEntity>
 */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, UserEntity::class);
    }

    public function findById(string $id): ?User
    {
        $entity = $this->find($id);

        if (!$entity) {
            return null;
        }

        return UserMapper::toDomain($entity);
    }

    public function findByEmailWithRefreshTokens(string $email): ?User
    {
        $entity = $this->createQueryBuilder('u')
            ->leftJoin('u.refreshTokens', 'rt')
            ->addSelect('rt')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if ($entity === null) {
            return null;
        }

        return UserMapper::toDomain($entity);
    }

    public function save(User $user, string $plainPassword): void
    {
        $entity = UserMapper::toEntity($user);

        $hashedPassword = $this->passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPassword($hashedPassword);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(UserEntity $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?User
    {
        $entity = $this->findOneBy(['email' => $email]);

        if (!$entity) {
            return null;
        }

        return UserMapper::toDomain($entity);
    }

    function findByActivationToken(string $activationToken): ?User
    {
       $entity = $this->findOneBy(['activationToken' => $activationToken]);
       return UserMapper::toDomain($entity);
    }

    function update(User $user): void
    {
        $entity = $this->find($user->getId());

        if($entity === null) {
            throw new UserNotFoundException();
        }

        $entity->setIsActive($user->isActive());
        $this->getEntityManager()->flush();
    }
}
