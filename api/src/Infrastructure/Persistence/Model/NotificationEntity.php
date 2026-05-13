<?php

namespace App\Infrastructure\Persistence\Model;


use App\Infrastructure\Persistence\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class NotificationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(

        #[ORM\Column(length: 255)]
        private string $userId,

        #[ORM\Column(length: 255)]
        private string $type,

        #[ORM\Column(length: 255)]
        private string $message,

        #[ORM\Column(length: 255)]
        private string $galleryId,

        #[ORM\Column(name: 'is_read', type: 'boolean')]
        private bool $read = false,

        #[ORM\Column(type: 'datetime_immutable')]
        private \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getGalleryId(): string
    {
        return $this->galleryId;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function setRead(bool $read): void
    {
        $this->read = $read;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}