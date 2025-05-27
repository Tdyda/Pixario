<?php

namespace App\Controller\Api\AuthTest;

use App\DTO\Response\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AuthTestController extends AbstractController
{
    #[Route('/api/auth-test', name: 'app_api_auth_test')]
    public function index(): JsonResponse
    {
        return $this->json(new SuccessResponse('TEST POZYTYWNY!'));
    }
}
