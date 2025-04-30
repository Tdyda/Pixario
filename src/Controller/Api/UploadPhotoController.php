<?php

namespace App\Controller\Api;

use App\DTO\Photo\PhotoRequestDTO;
use App\Service\FileStorageService;
use App\Service\PhotoRoomService;
use App\Service\Validation\DtoValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UploadPhotoController extends AbstractController
{
    #[Route('/api/upload/photo', name: 'app_api_upload_photo', methods: ['POST'])]
    public function upload(
        Request            $request,
        PhotoRoomService   $photoRoomService,
        FileStorageService $fileStorageService,
        DtoValidator       $dtoValidator
    ): JsonResponse
    {
        $dto = new PhotoRequestDTO($request->files->get('photo'));
        $dtoValidator->validate($dto);

        try {
            $retouchedImageBinary = $photoRoomService->retouchImage($dto->getPhoto());
            $publicPath = $fileStorageService->saveImage($retouchedImageBinary);

            return $this->json([
                'message' => 'Photo uploaded and processed successfully.',
                'path' => $publicPath
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => "Processing failed",
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
