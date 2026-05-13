<?php

namespace App\Api\Feature\Account\UI\Logout;

use App\Application\Feature\Account\RefreshToken\Revoke\RevokeAllTokensHandler;
use App\Application\Feature\Account\RefreshToken\Revoke\RevokeTokenHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutFeature extends AbstractController
{
    #[Route('/api/auth/logout', name: 'app_account_logout', methods: ['POST'])]
    public function logout(
        Request $request,
        RevokeTokenHandler $handler,
    ): JsonResponse {
        $refreshToken = $request->cookies->get('refresh_token');

        if ($refreshToken) {
            $handler->handle($refreshToken);
        }

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('access_token', '/', null, false, true, 'lax');
        $response->headers->clearCookie('refresh_token', '/', null, false, true, 'lax');

        return $response;
    }

    #[Route('api/auth/logout-from-all-devices', name: 'app_account_logout_from_all_devices', methods: ['POST'])]
    public function logoutFromAllDevices(
        Request $request,
        RevokeAllTokensHandler $handler
    ): JsonResponse {
        $token = $request->cookies->get('access_token');

        if ($token) {
            $handler->Handle($token);
        }

        $response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
        $response->headers->clearCookie('access_token', '/', null, true, true, 'Strict');
        $response->headers->clearCookie('refresh_token', '/', null, true, true, 'Strict');

        return $response;
    }
}