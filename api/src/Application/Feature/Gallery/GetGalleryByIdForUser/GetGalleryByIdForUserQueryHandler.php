<?php

namespace App\Application\Feature\Gallery\GetGalleryByIdForUser;

use App\Api\Feature\ImageResponse;
use App\Api\Http\Exception\InvalidCredentialsException;
use App\Application\Feature\Gallery\GalleryResponse;
use App\Application\Port\GalleryRepositoryInterface;
use App\Application\Port\UserRepositoryInterface;
use App\Core\Domain\Image;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class GetGalleryByIdForUserQueryHandler
{
    public function __construct(
        private GalleryRepositoryInterface $galleryRepository,
        private UserRepositoryInterface $userRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function handle(GetGalleryByIdForUserQuery $query): GalleryResponse
    {
        $gallery = $this->galleryRepository->findById($query->id);
        $user = $this->userRepository->findById($query->userId);

        if ($user === null || $gallery->getGalleryOwner() !== $user->getId()) {
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
            $gallery->getCreatedAt(),
        );
    }
}