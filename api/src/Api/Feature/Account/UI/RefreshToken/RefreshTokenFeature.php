<?php

namespace App\Api\Feature\Account\UI\RefreshToken;

use App\Api\Http\Response\SuccessResponse;
use App\Api\Http\Validation\RequestValidator;
use App\Api\Security\Jwt\JwtService;
use App\Application\Feature\Account\RefreshToken\Refresh\RefreshTokenHandler;
use App\Application\Feature\Account\SignIn\AuthTokensDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RefreshTokenFeature extends AbstractController
{
    #[Route('/api/auth/refresh', name: 'app_account_refresh', methods: ['POST'])]
    public function __invoke(
        Request $request,
        RequestValidator $validator,
        RefreshTokenHandler $handler,
        JwtService $jwtService
    ): JsonResponse {
        $dto = new RefreshRequest();
        $dto->refreshToken = $request->cookies->get('refresh_token');

        $validator->validate($dto);

        $tokens = $handler->handle($dto->refreshToken);

        $response = $this->json(
            new SuccessResponse(
                'Token odświeżony'
            ),
            Response::HTTP_CREATED
        );

        $response->headers->setCookie(
            Cookie::create('access_token')
                ->withValue($tokens->accessToken)
                ->withHttpOnly()
                ->withSecure()
                ->withSameSite('Strict')
                ->withPath('/')
                ->withExpires($jwtService->getTokenExpiry('access'))
        );

        $response->headers->setCookie(
            Cookie::create('mercureAuthorization')
                ->withValue($tokens->mercureToken)
                ->withHttpOnly()
                ->withSecure()
                ->withSameSite('Strict')
                ->withPath('/.well-known/mercure')
                ->withExpires($jwtService->getTokenExpiry('access'))
        );

        $response->headers->setCookie(
            Cookie::create('refresh_token')
                ->withValue($tokens->refreshToken)
                ->withHttpOnly()
                ->withSecure()
                ->withSameSite('Strict')
                ->withPath('/')
                ->withExpires($jwtService->getTokenExpiry('refresh'))
        );

        return $response;
    }
}