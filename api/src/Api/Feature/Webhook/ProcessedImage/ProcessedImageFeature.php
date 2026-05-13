<?php

namespace App\Api\Feature\Webhook\ProcessedImage;

use App\Api\Feature\Webhook\ResultResponse;
use App\Application\Feature\ProcessedImage\ProcessedImageCommand;
use App\Application\Feature\ProcessedImage\ProcessedImageCommandHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProcessedImageFeature extends AbstractController
{
    #[Route('/webhooks/processed-image', name: 'app_webhooks_processed_image', methods: ['POST'])]
    public function index(
        Request $request,
        ProcessedImageCommandHandler $handler,
        SerializerInterface $serializer
    ): JsonResponse {
        $galleryId = $request->request->get('galleryId');
        $resultsJson = $request->request->get('results');
        $files = $request->files->get('files', []);

        if (!$galleryId) {
            return $this->json(['message' => 'Missing galleryId.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$resultsJson) {
            return $this->json(['message' => 'Missing results JSON.'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $files = array_values(
            array_filter(
                $files,
                fn($file) => $file instanceof UploadedFile
            )
        );

        if ($files === []) {
            return $this->json(['message' => 'No valid files uploaded.'], Response::HTTP_BAD_REQUEST);
        }

        /** @var ResultResponse[] $result */
        $result = $serializer->deserialize(
            $resultsJson,
            ResultResponse::class . '[]',
            'json'
        );

        $response = $handler->handle(
            new ProcessedImageCommand(
                $files,
                Uuid::fromString($galleryId),
                $result
            )
        );

        return $this->json([
            'message' => 'Images uploaded and processed successfully.',
            'galleryId' => $response->galleryId,
            'pathsArray' => $response->pathsArray,
            'results' => $response->results,
        ]);
    }
}