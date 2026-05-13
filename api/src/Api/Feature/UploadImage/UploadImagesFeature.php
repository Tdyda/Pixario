<?php

namespace App\Api\Feature\UploadImage;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\UploadImage\UploadImagesCommand;
use App\Application\Feature\UploadImage\UploadImagesCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UploadImagesFeature extends AbstractController
{
    #[Route('/api/upload/images', name: 'app_api_upload_images', methods: ['POST'])]
    public function upload(
        Request $request,
        RequestValidator $validator,
        UploadImagesCommandHandler $handler,
    ): JsonResponse {
        $dto = new UploadImagesRequest($request->files->get('images'));
        $galleryId = $request->get('galleryId');
        $validator->validate($dto);

        $command = new UploadImagesCommand(
            $galleryId,
            $dto->getImages()
        );

        $result = $handler->handle($command);

        return new JsonResponse($result, Response::HTTP_ACCEPTED);
    }
}
