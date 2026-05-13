<?php

namespace App\Core\Domain;

final readonly class Notification
{
    private function __construct(
        private ?int $id = null,
        private string $userId,
        private string $type,
        private string $message,
        private string $galleryId,
        private bool $read = false,
        private ?\DateTimeImmutable $createdAt = null,
    ) {
    }

    public static function create(
        ?int $id,
        string $userId,
        string $type,
        string $message,
        string $galleryId,
        bool $read = false,
        ?\DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ): self {
        return new self($id, $userId, $type, $message, $galleryId, $read, $createdAt);
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}