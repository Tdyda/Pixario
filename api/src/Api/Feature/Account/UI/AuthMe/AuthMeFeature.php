<?php

namespace App\Api\Feature\Account\UI\AuthMe;

use App\Api\Security\Exception\InvalidTokenException;
use App\Application\Feature\Account\AuthMe\AuthMeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthMeFeature extends AbstractController
{
    #[Route('/api/auth/me', name: 'app_account_me', methods: ['GET'])]
    public function __invoke(
        Request $request,
        AuthMeHandler $handler,
    ): JsonResponse {
        $token = $request->cookies->get('access_token');

        if (!$token) {
            throw new InvalidTokenException();
        }

        $user = $handler->handle($token);

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ]
        ], Response::HTTP_OK);
    }
}