<?php

namespace App\Application\Feature\Gallery\Create;

use App\Application\Feature\Gallery\GalleryResponse;
use App\Application\Port\GalleryRepositoryInterface;
use App\Core\Domain\Gallery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final readonly class CreateGalleryCommandHandler
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private GalleryRepositoryInterface $galleryRepository,
    ) {
    }

    public function handle(CreateGalleryCommand $command): GalleryResponse
    {
        $array = [];

        $gallery = Gallery::create(
            Uuid::uuid7()->toString(),
            $command->name,
            $command->emailAddress,
            $this->passwordHasher->hash($command->password),
            $command->galleryOwner,
            $array
        );

        $this->galleryRepository->save($gallery);

        return new GalleryResponse(
            $gallery->getId(),
            $gallery->getName(),
            $gallery->getEmailAddress(),
            $gallery->getGalleryOwner(),
            $gallery->getImages(),
            $gallery->getCreatedAt()
        );
    }
}