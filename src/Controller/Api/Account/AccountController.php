<?php

namespace App\Controller\Api\Account;

use App\DTO\Response\SuccessResponse;
use App\Service\AuthService;
use App\Service\JwtService;
use App\Service\Validation\DtoValidator;
use App\Service\Validation\SignInRequest;
use App\Service\Validation\SignUpRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
                JsonResponse::HTTP_OK);

            $response->headers->setCookie(
                Cookie::create('access_token')
                    ->withValue($tokens['access_token'])
                    ->withHttpOnly(true)
                    ->withSecure(true)
                    ->withSameSite('Strict')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('access'))
            );

            $response->headers->setCookie(
                Cookie::create('refresh_token')
                    ->withValue($tokens['refresh_token'])
                    ->withHttpOnly(true)
                    ->withSecure(true)
                    ->withSameSite('Strict')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('refresh'))
            );

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
                JsonResponse::HTTP_CREATED);
        } catch (\LogicException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
