<?php

namespace App\Api\Feature\Gallery\DeleteImageFromGallery;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Gallery\DeleteImageFromGallery\DeleteImageFromGalleryCommand;
use App\Application\Feature\Gallery\DeleteImageFromGallery\DeleteImageFromGalleryCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DeleteImageFromGalleryFeature extends AbstractController
{
    #[Route('api/gallery/{galleryId}', name: 'gallery_delete_image_from_gallery', methods: ['DELETE'])]
    public function deleteImages(
        Request $request,
        DeleteImageFromGalleryCommandHandler $handler,
        SerializerInterface $serializer,
        RequestValidator $validator,
        string $galleryId
    ): JsonResponse {
        /** @var DeleteImageFromGalleryRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), DeleteImageFromGalleryRequest::class, 'json');
        $validator->validate($dto);

        $handler->handle(new DeleteImageFromGalleryCommand($galleryId, $dto->imageIds));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}