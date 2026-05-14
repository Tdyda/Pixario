<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class UserEntity implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(length: 180)]
    private string $email;

    /** @var list<string> */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive;

    #[ORM\Column(type: 'string')]
    private string $activationToken;

    /** @var Collection<int, RefreshTokenEntity> */
    #[ORM\OneToMany(
        targetEntity: RefreshTokenEntity::class,
        mappedBy: 'userRef',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $refreshTokens;

    #[ORM\OneToMany(
        targetEntity: GalleryEntity::class,
        mappedBy: 'ownerRef',
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private Collection $galleries;

    public function __construct(
        string $id,
        string $email,
        bool $isActive,
        string $activationToken,
        array $roles = [],
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->isActive = $isActive;
        $this->activationToken = $activationToken;
        $this->password = '';
        $this->roles = $roles;
        $this->refreshTokens = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        return array_values(array_unique($this->roles));
    }

    public function setRoles(array $roles): void
    {
        $this->roles = array_values(array_unique($roles));
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    public function setActivationToken(string $activationToken): void
    {
        $this->activationToken = $activationToken;
    }

    /** @return Collection<int, RefreshTokenEntity> */
    public function getRefreshTokens(): Collection
    {
        return $this->refreshTokens;
    }

    public function addRefreshToken(RefreshTokenEntity $token): void
    {
        if (!$this->refreshTokens->contains($token)) {
            $this->refreshTokens->add($token);
            $token->setUserRef($this);
        }
    }

    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(GalleryEntity $gallery): void
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries->add($gallery);
            $gallery->setOwnerRef($this);
        }
    }

    public function removeRefreshToken(RefreshTokenEntity $token): void
    {
        if ($this->refreshTokens->removeElement($token)) {
            if ($token->getUserRef() === $this) {
                $token->setUserRef(null);
            }
        }
    }
}