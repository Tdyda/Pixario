<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Repository\RecoverTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecoverTokenRepository::class)]
class RecoverTokenEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    public function __construct(
        #[ORM\ManyToOne(targetEntity: UserEntity::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private readonly UserEntity $userRef,

        #[ORM\Column(type: 'string', length: 255, unique: true)]
        private readonly string $tokenHash,

        #[ORM\Column(type: 'datetime_immutable')]
        private readonly DateTimeImmutable $expiresAt,

        #[ORM\Column(type: 'datetime_immutable')]
        private readonly DateTimeImmutable $createdAt,

        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        private ?DateTimeImmutable $usedAt = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRef(): UserEntity
    {
        return $this->userRef;
    }

    public function getTokenHash(): string
    {
        return $this->tokenHash;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUsedAt(): ?DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function setUsedAt(DateTimeImmutable $usedAt): void
    {
        $this->usedAt = $usedAt;
    }
}