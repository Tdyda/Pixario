<?php

namespace App\Api\Feature\Account\UI\SignUp;

use App\Api\Http\Response\SuccessResponse;
use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Account\SignUp\SignUpCommand;
use App\Application\Feature\Account\SignUp\SignUpHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SignUpFeature extends AbstractController
{
    #[Route('/api/auth/sign-up', name: 'app_account_sign_up', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        RequestValidator $validator,
        SignUpHandler $handler
    ): JsonResponse {
        $command = $serializer->deserialize($request->getContent(), SignUpCommand::class, 'json');
        $validator->validate($command);

        $handler->handle($command);

        return $this->json(
            new SuccessResponse(
                'Użytkownik został zarejestrowany',
                ['email' => $command->email]
            ),
            Response::HTTP_CREATED
        );
    }
}