<?php

namespace App\Core\Domain;

final readonly class Image
{
    private function __construct(
        private ?int $id,
        private string $name,
        private \DateTimeImmutable $uploaded_at,
        private string $galleryId,
    ) {
    }

    public static function restore(int $id, string $name, string $galleryId): self
    {
        return new self($id, $name, new \DateTimeImmutable(), $galleryId);
    }

    public static function create(string $name, string $galleryId): self
    {
        return new self(null, $name, new \DateTimeImmutable(), $galleryId);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUploadedAt(): \DateTimeImmutable
    {
        return $this->uploaded_at;
    }

    public function getGallery(): string
    {
        return $this->galleryId;
    }
}