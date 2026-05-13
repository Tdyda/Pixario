<?php

namespace App\Application\Feature\Gallery\GetByIdForGuest;

use App\Api\Feature\ImageResponse;
use App\Api\Http\Exception\InvalidCredentialsException;
use App\Application\Feature\Gallery\GalleryResponse;
use App\Application\Port\GalleryRepositoryInterface;
use App\Core\Domain\Image;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class GetGalleryByIdForGuestCommandHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private PasswordHasherInterface $passwordHasher,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function handle(GetGalleryByIdForGuestCommand $query): GalleryResponse
    {
        $gallery = $this->galleryRepository->findById($query->id);
        if (!$gallery) {
            throw new NotFoundHttpException('Gallery not found');
        }

        if ($gallery->getEmailAddress() !== $query->emailAddress || !$this->passwordHasher->verify(
                $gallery->getPassword(),
                $query->password
            )) {
            throw new InvalidCredentialsException();
        }

        $images = array_map(
            fn(Image $i) => new ImageResponse(
                $i->getId(),
                $i->getName(),
                $this->urlGenerator->generate(
                    'photo_show',
                    [
                        'dir' => $gallery->getId(),
                        'name' => basename($i->getName())
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                $i->getUploadedAt()
            ),
            $gallery->getImages()
        );

        return new GalleryResponse(
            $gallery->getId(),
            $gallery->getName(),
            $gallery->getEmailAddress(),
            $gallery->getGalleryOwner(),
            $images,
            $gallery->getCreatedAt()
        );
    }
}