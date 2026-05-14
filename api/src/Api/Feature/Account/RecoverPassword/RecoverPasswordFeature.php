<?php

namespace App\Api\Feature\Account\RecoverPassword;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Account\RecoverPassword\RecoverPasswordCommand;
use App\Application\Feature\Account\RecoverPassword\RecoverPasswordCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RecoverPasswordFeature extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('api/recover-password', name: 'api_recover-password', methods: ['POST'])]
    public function recoverPassword(
        Request $request,
        REquestValidator $validator,
        SerializerInterface $serializer,
        RecoverPasswordCommandHandler $handler,

    ): JsonResponse
    {
        /** @var RecoverPasswordRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), RecoverPasswordRequest::class, 'json');
        $validator->validate($dto);

        $handler->handle(new RecoverPasswordCommand($dto->recoverToken, $dto->newPassword));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}