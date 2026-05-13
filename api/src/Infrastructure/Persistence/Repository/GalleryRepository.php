<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\GalleryRepositoryInterface;
use App\Core\Domain\Gallery;
use App\Core\Domain\Image;
use App\Infrastructure\Persistence\Mapper\GalleryMapper;
use App\Infrastructure\Persistence\Mapper\ImageMapper;
use App\Infrastructure\Persistence\Model\GalleryEntity;
use App\Infrastructure\Persistence\Model\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @extends ServiceEntityRepository<GalleryEntity>
 */
class GalleryRepository extends ServiceEntityRepository implements GalleryRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, GalleryEntity::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @throws ORMException
     */
    function save(Gallery $gallery): void
    {
        $ownerRef = $this->entityManager->getReference(
            UserEntity::class,
            $gallery->getGalleryOwner()
        );

        $entity = GalleryMapper::toEntity($gallery, $ownerRef);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    function update(Gallery $gallery): void
    {
        $entity = $this->find(Uuid::fromString($gallery->getId()));

        if ($entity === null) {
            throw new \LogicException('Gallery not found.');
        }

        $domainImageIds = array_filter(
            array_map(
                fn(Image $image) => $image->getId(),
                $gallery->getImages()
            )
        );

        foreach ($entity->getImages() as $imageEntity) {
            if (!in_array($imageEntity->getId(), $domainImageIds, true)) {
                $entity->removePhoto($imageEntity);
            }
        }

        foreach ($gallery->getImages() as $image) {
            if ($image->getId() !== null) {
                continue;
            }

            $entity->addPhoto(ImageMapper::toEntity($image, $entity));
        }

        $this->getEntityManager()->flush();
    }

    function findById(string $id): ?Gallery
    {
        $entity = $this->find($id);

        return $entity
            ? GalleryMapper::toDomain($entity)
            : null;
    }

    function findByOwnerId(string $ownerId): array
    {
        $entities = $this->createQueryBuilder('g')
            ->andWhere('g.ownerRef = :ownerId')
            ->setParameter('ownerId', $ownerId)
            ->getQuery()
            ->getResult();

        return array_map(
            static fn(GalleryEntity $entity): Gallery => GalleryMapper::toDomain($entity),
            $entities
        );
    }

    function deleteById(string $galleryId): void
    {
        $entity = $this->find($galleryId);
        if ($entity === null) {
            throw new \LogicException('Gallery not found.');
        }

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
