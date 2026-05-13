<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Application\Port\NotificationRepositoryInterface;
use App\Core\Domain\Notification;
use App\Infrastructure\Persistence\Mapper\NotificationMapper;
use App\Infrastructure\Persistence\Model\NotificationEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationEntity>
 */
class NotificationRepository extends ServiceEntityRepository implements NotificationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationEntity::class);
    }

    function save(Notification $notification): void
    {
        $entity = NotificationMapper::toEntity($notification);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    function markAsRead(string $userId): void
    {
        $notifications = $this->getAllUnreadNotificationsForUser($userId);
        foreach ($notifications as $notification) {
            $notification->setRead(true);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @return NotificationEntity[]
     */
    function getAllUnreadNotificationsForUser(string $userId): array
    {
        return $this->findBy([
            'userId' => $userId,
            'read' => false
        ]);
    }
}
