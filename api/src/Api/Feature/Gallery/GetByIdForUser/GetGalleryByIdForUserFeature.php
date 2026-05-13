<?php

namespace App\Api\Feature\Gallery\GetByIdForUser;

use App\Application\Feature\Gallery\GetGalleryByIdForUser\GetGalleryByIdForUserQuery;
use App\Application\Feature\Gallery\GetGalleryByIdForUser\GetGalleryByIdForUserQueryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetGalleryByIdForUserFeature extends AbstractController
{
    #[Route('/api/gallery/{galleryUrl}/{userId}', name: 'app_gallery_url', methods: ['GET'])]
    public function __invoke(
        GetGalleryByIdForUserQueryHandler $handler,
        string $galleryUrl,
        string $userId
    ): JsonResponse {
        $result = $handler->handle(
            new GetGalleryByIdForUserQuery(
                $galleryUrl,
                $userId
            )
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }
}