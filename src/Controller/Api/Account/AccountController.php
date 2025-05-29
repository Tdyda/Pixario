<?php

namespace App\Controller\Api\Account;

use App\DTO\Auth\RefreshRequest;
use App\DTO\Auth\SignInRequest;
use App\DTO\Auth\SignUpRequest;
use App\DTO\Response\SuccessResponse;
use App\Exception\InvalidTokenException;
use App\Service\Auth\AuthService;
use App\Service\Auth\RefreshTokenManager;
use App\Service\Auth\UserContextService;
use App\Service\Token\JwtService;
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

        $tokens = $authService->signIn($dto);
        $response = new JsonResponse(
            new SuccessResponse('Użytkownik został zalogowany'),
            Response::HTTP_OK);

        $response->headers->setCookie(
            Cookie::create('access_token')
                ->withValue($tokens->accessToken)
                ->withHttpOnly(true)
                ->withSecure(true)
                ->withSameSite('Strict')
                ->withPath('/')
                ->withExpires($jwtService->getTokenExpiry('access'))
        );

        if ($dto->rememberMe === true) {
            $response->headers->setCookie(
                Cookie::create('refresh_token')
                    ->withValue($tokens->refreshToken)
                    ->withHttpOnly(true)
                    ->withSecure(false)
                    ->withSameSite('Lax')
                    ->withPath('/')
                    ->withExpires($jwtService->getTokenExpiry('refresh'))
            );
        }

        return $response;
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

        $authService->signUp($dto);

        return $this->json(
            new SuccessResponse('Użytkownik został zarejestrowany'),
            Response::HTTP_CREATED);
    }

    #[Route('/api/auth/refresh', name: 'app_account_refresh', methods: ['POST'])]
    public function refresh(
        Request      $request,
        DtoValidator $validator,
        AuthService  $authService,
        JwtService   $jwtService
    ): JsonResponse
    {
        $dto = new RefreshRequest();
        $dto->refreshToken = $request->cookies->get('refresh_token');

        $validator->validate($dto);

        $accessToken = $authService->refreshAccessToken($dto->refreshToken);

        $response = $this->json(new SuccessResponse('Token odświeżony'));

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
    }

    #[Route('/api/auth/me', name: 'app_account_me', methods: ['GET'])]
    public function authMe(
        Request            $request,
        UserContextService $userContextService,
    ): JsonResponse
    {
        $token = $request->cookies->get('access_token');

        if (!$token) {
            throw new InvalidTokenException();
        }

        $user = $userContextService->authMe($token);

        return $this->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles,
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/api/auth/logout', name: 'app_account_logout', methods: ['POST'])]
    public function logout(
        Request             $request,
        RefreshTokenManager $refreshTokenManager,
    ) : JsonResponse
    {
        $refreshToken = $request->cookies->get('refresh_token');

        if ($refreshToken) {
            $refreshTokenManager->revokeSingleToken($refreshToken);
        }

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('access_token', '/', null, true, true, 'Strict');
        $response->headers->clearCookie('refresh_token', '/', null, true, true, 'Strict');

        return $response;
    }

    #[Route('api/auth/logout-from-all-devices', name: 'app_account_logout_from_all_devices', methods: ['POST'])]
    public function logoutFromAllDevices(
        Request             $request,
        RefreshTokenManager $refreshTokenManager,
    ): JsonResponse
    {
        $token = $request->cookies->get('access_token');

        if ($token) {
            $refreshTokenManager->revokeAllTokensFromAccessToken($token);
        }

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('access_token', '/', null, true, true, 'Strict');
        $response->headers->clearCookie('refresh_token', '/', null, true, true, 'Strict');

        return $response;
    }
}
