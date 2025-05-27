<?php

namespace App\Controller\Api\Account;

use App\DTO\Auth\RefreshRequest;
use App\DTO\Auth\SignInRequest;
use App\DTO\Auth\SignUpRequest;
use App\DTO\Response\ErrorResponse;
use App\DTO\Response\SuccessResponse;
use App\Service\AuthService;
use App\Service\JwtService;
use App\Service\Validation\DtoValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class AccountController extends AbstractController
{
    #[Route('/api/auth/sign-in', name: 'app_account_sign_in', methods: ['POST'])]
    public function signIn(
        Request             $request,
        SerializerInterface $serializer,
        DtoValidator        $validator,
        AuthService         $authService,
        JwtService          $jwtService
    ): JsonResponse
    {
        $dto = $serializer->deserialize($request->getContent(), SignInRequest::class, 'json');
        $validator->validate($dto);

        try {
            $tokens = $authService->signIn($dto);
            $response = new JsonResponse(new SuccessResponse('Użytkownik został zalogowany'),
                Response::HTTP_OK);

            $response->headers->setCookie(
                Cookie::create('access_token')
                    ->withValue($tokens['access_token'])
                    ->withHttpOnly(true)
                    ->withSecure(true)
                    ->withSameSite('Strict')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('access'))
            );

            if ($dto->rememberMe === true) {
                $response->headers->setCookie(
                    Cookie::create('refresh_token')
                        ->withValue($tokens['refresh_token'])
                        ->withHttpOnly(true)
                        ->withSecure(false)
                        ->withSameSite('Lax')
                        ->withPath('/')
                        ->withExpires($jwtService->getTokenExpiry('refresh'))
                );
            }

            return $response;
        } catch (\LogicException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/auth/sign-up', name: 'app_account_sign_up', methods: ['POST'])]
    public function signUp(
        Request             $request,
        SerializerInterface $serializer,
        DtoValidator        $validator,
        AuthService         $authService
    ): JsonResponse
    {
        $dto = $serializer->deserialize($request->getContent(), SignUpRequest::class, 'json');
        $validator->validate($dto);

        try {
            $authService->signUp($dto);

            return $this->json(
                new SuccessResponse('Użytkownik został zarejestrowany'),
                Response::HTTP_CREATED);
        } catch (\LogicException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/auth/refresh', name: 'app_account_refresh', methods: ['POST'])]
    public function refresh(
        Request             $request,
        SerializerInterface $serializer,
        DtoValidator        $validator,
        AuthService         $authService,
        JwtService          $jwtService
    ): JsonResponse
    {
        $dto = $serializer->deserialize($request->getContent(), RefreshRequest::class, 'json');
        $refreshCookie = $request->cookies->get('refresh_token');
        $dto->setRefreshToken($refreshCookie);

        $validator->validate($dto);

        try {
            $accessToken = $authService->refresh($dto);

            $response = new JsonResponse(new SuccessResponse('Refresh token poprawny!'));

            $response->headers->setCookie(
                Cookie::create('access_token')
                    ->withValue($accessToken)
                    ->withHttpOnly(true)
                    ->withSecure(true)
                    ->withSameSite('Strict')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('access'))
            );

            return $response;
        } catch (\LogicException $e) {
            return $this->json([new ErrorResponse($e->getMessage())], 400);
        }

    }
}
