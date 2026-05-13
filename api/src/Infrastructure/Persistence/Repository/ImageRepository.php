<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\ImageRepositoryInterface;
use App\Infrastructure\Persistence\Model\ImageEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageEntity>
 */
class ImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageEntity::class);
    }
}
