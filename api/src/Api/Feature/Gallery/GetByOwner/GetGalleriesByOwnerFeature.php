<?php

namespace App\Api\Feature\Gallery\GetByOwner;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Gallery\GetByOwner\GetGalleriesByOwnerQuery;
use App\Application\Feature\Gallery\GetByOwner\GetGalleriesByOwnerQueryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetGalleriesByOwnerFeature extends AbstractController
{
    #[Route('/api/gallery/{ownerId}', name: 'app_gallery_by_owner', methods: ['GET'])]
    public function __invoke(
        GetGalleriesByOwnerQueryHandler $handler,
        RequestValidator $validator,
        string $ownerId
    ): JsonResponse {
        $request = new GetGalleriesByOwnerRequest($ownerId);
        $validator->validate($request);

        $result = $handler->handle(new GetGalleriesByOwnerQuery($request->id));

        return new JsonResponse($result, Response::HTTP_OK);
    }
}