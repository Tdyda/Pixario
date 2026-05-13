<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class GalleryEntity
{
    #[ORM\Id]
    #[ORM\Column(length: 44)]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'galleries')]
    #[ORM\JoinColumn(name: "owner_id", referencedColumnName: "id", nullable: false)]
    private UserEntity $ownerRef;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, ImageEntity>
     */
    #[ORM\OneToMany(targetEntity: ImageEntity::class, mappedBy: 'gallery', cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    private Collection $images;

    public function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        UserEntity $owner
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->emailAddress = $email;
        $this->password = $password;
        $this->createdAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->ownerRef = $owner;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function getOwnerRef(): UserEntity
    {
        return $this->ownerRef;
    }

    public function setOwnerRef(?UserEntity $ownerRef): static
    {
        $this->ownerRef = $ownerRef;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, ImageEntity>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addPhoto(ImageEntity $photo): static
    {
        if (!$this->images->contains($photo)) {
            $this->images->add($photo);
            $photo->setGallery($this);
        }

        return $this;
    }

    public function removePhoto(ImageEntity $photo): static
    {
        if ($this->images->removeElement($photo)) {
            if ($photo->getGalleryRef() === $this) {
                $photo->setGallery(null);
            }
        }

        return $this;
    }
}
