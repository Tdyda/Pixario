<?php

namespace App\Api\Feature\Gallery\Delete;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Gallery\Delete\DeleteGalleryByIdCommand;
use App\Application\Feature\Gallery\Delete\DeleteGalleryByIdCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DeleteGalleryFeature extends AbstractController
{
    #[Route('api/gallery', name: 'gallery_delete', methods: ['DELETE'])]
    public function deleteGalleryById(
        Request $request,
        DeleteGalleryByIdCommandHandler $handler,
        SerializerInterface $serializer,
        RequestValidator $validator
    ): JsonResponse {
        /** @var DeleteGalleryRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), DeleteGalleryRequest::class, 'json');
        $validator->validate($dto);

        $handler->handle(new DeleteGalleryByIdCommand($dto->galleryId));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}