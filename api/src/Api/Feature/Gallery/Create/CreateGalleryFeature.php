<?php

namespace App\Api\Feature\Gallery\Create;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Gallery\Create\CreateGalleryCommand;
use App\Application\Feature\Gallery\Create\CreateGalleryCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateGalleryFeature extends AbstractController
{
    #[Route('/api/gallery', name: 'app_gallery', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CreateGalleryCommandHandler $handler,
        RequestValidator $validator
    ): JsonResponse {
        $dto = $serializer->deserialize($request->getContent(), CreateGalleryRequest::class, 'json');
        $validator->validate($dto);

        $command = new CreateGalleryCommand(
            $dto->name,
            $dto->emailAddress,
            $dto->password,
            $dto->ownerId,
        );

        $result = $handler->handle($command);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }
}