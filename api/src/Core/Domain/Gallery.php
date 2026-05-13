<?php

namespace App\Core\Domain;

final class Gallery
{
    private function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $emailAddress,
        private readonly string $password,
        private readonly string $galleryOwner,
        private readonly \DateTimeImmutable $created_at,
        /**
         * @var Image[]
         */
        private array $images
    ) {
    }

    public static function create(
        string $id,
        string $name,
        string $emailAddress,
        string $password,
        string $galleryOwner,
        array $images,
        ?\DateTimeImmutable $created_at = null
    ): self {
        return new self(
            $id, $name, $emailAddress, $password, $galleryOwner, $created_at ?? new \DateTimeImmutable(), $images
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getGalleryOwner(): string
    {
        return $this->galleryOwner;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function addPhoto(Image $image): void
    {
        $this->images[] = $image;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }
}