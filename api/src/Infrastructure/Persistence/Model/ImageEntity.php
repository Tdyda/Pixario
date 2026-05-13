<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class ImageEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploaded_at = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(name: "gallery_id", referencedColumnName: "id", nullable: false)]
    private ?GalleryEntity $gallery = null;

    public function __construct(string $name, GalleryEntity $gallery, $uploaded_at)
    {
        $this->name = $name;
        $this->gallery = $gallery;
        $this->uploaded_at = $uploaded_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTimeImmutable $uploaded_at): static
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }

    public function getGalleryRef(): ?GalleryEntity
    {
        return $this->gallery;
    }

    public function setGallery(?GalleryEntity $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }
}
