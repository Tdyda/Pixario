<?php

namespace App\Api\Feature\Gallery\GetByIdForGuest;

use App\Api\Http\Validation\RequestValidator;
use App\Api\Security\Jwt\JwtService;
use App\Application\Feature\Gallery\GetByIdForGuest\GetGalleryByIdForGuestCommand;
use App\Application\Feature\Gallery\GetByIdForGuest\GetGalleryByIdForGuestCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class GetGalleryByIdForGuestFeature extends AbstractController
{
    #[Route('/api/gallery/get', name: 'app_gallery_get', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        GetGalleryByIdForGuestCommandHandler $handler,
        RequestValidator $validator,
        JwtService $jwtService
    ): JsonResponse {
        $dto = $serializer->deserialize($request->getContent(), GetGalleryByIdForGuestRequest::class, 'json');
        $validator->validate($dto);

        $command = new GetGalleryByIdForGuestCommand(
            $dto->id,
            $dto->emailAddress,
            $dto->password
        );

        $result = $handler->handle($command);
        $token = $jwtService->createAccessToken(null, $command->id);
        $response = new JsonResponse(
            $result,
            Response::HTTP_OK
        );

        $response->headers->setCookie(
            Cookie::create('access_token')
                ->withValue($token)
                ->withHttpOnly(true)
                ->withSecure(false)
                ->withSameSite(Cookie::SAMESITE_LAX)
                ->withPath('/')
                ->withExpires($jwtService->getTokenExpiry('access'))
        );

        return $response;
    }
}