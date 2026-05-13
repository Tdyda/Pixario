<?php

namespace App\Api\Feature\Account\UI\SignIn;

use App\Api\Http\Response\SuccessResponse;
use App\Api\Http\Validation\RequestValidator;
use App\Api\Security\Jwt\JwtService;
use App\Application\Feature\Account\SignIn\SignInCommand;
use App\Application\Feature\Account\SignIn\SignInHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SignInFeature extends AbstractController
{
    #[Route('/api/auth/sign-in', name: 'app_account_sign_in', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        RequestValidator $validator,
        SignInHandler $handler,
        JwtService $jwtService
    ): JsonResponse {
        $command = $serializer->deserialize($request->getContent(), SignInCommand::class, 'json');
        $validator->validate($command);

        $tokens = $handler->handle($command);
        $response = new JsonResponse(
            new SuccessResponse('Użytkownik został zalogowany'),
            Response::HTTP_OK
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

        if ($command->rememberMe === true) {
            $response->headers->setCookie(
                Cookie::create('refresh_token')
                    ->withValue($tokens->refreshToken)
                    ->withHttpOnly()
                    ->withSecure()
                    ->withSameSite('Strict')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('refresh'))
            );
        }

        return $response;
    }
}