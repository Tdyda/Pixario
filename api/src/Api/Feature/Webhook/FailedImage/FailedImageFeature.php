<?php

namespace App\Api\Feature\Webhook\FailedImage;

use App\Api\Feature\Webhook\ResultResponse;
use App\Application\Feature\FailedImage\FailedImageCommand;
use App\Application\Feature\FailedImage\FailedImageCommandHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FailedImageFeature extends AbstractController
{
    /**
     * @throws ExceptionInterface
     * @throws \JsonException
     */
    #[Route('/webhooks/failed-image', name: 'app_webhooks_failed_image', methods: ['POST'])]
    public function handleFailedImage(
        Request $request,
        SerializerInterface $serializer,
        FailedImageCommandHandler $handler,
    ) {
        $galleryId = $request->request->get('galleryId');
        $resultsJson = $request->request->get('results');

        if (!$galleryId) {
            return $this->json(['message' => 'Missing galleryId.'], Response::HTTP_BAD_REQUEST);
        }

        if (!$resultsJson) {
            return $this->json(['message' => 'Missing results JSON.'], Response::HTTP_BAD_REQUEST);
        }

        /** @var ResultResponse[] $result */
        $result = $serializer->deserialize(
            $resultsJson,
            ResultResponse::class . '[]',
            'json'
        );

        $handler->handle(
            new FailedImageCommand(
                Uuid::fromString($galleryId),
                $result
            )
        );

        return $this->json([
            'message' => "Images processing failed",
            $result
        ], Response::HTTP_OK);
    }
}