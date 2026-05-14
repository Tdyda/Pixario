<?php

namespace App\Api\Feature\Account\RecoverPassword;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Account\RecoverPassword\CreateRecoverTokenCommand;
use App\Application\Feature\Account\RecoverPassword\CreateRecoverTokenCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateRecoverTokenFeature extends AbstractController
{
    #[Route('api/recover-token', name: 'recover_token_create', methods: ['POST'])]
    public function createRecoverToken(
        Request $request,
        SerializerInterface $serializer,
        RequestValidator $validator,
        CreateRecoverTokenCommandHandler $handler
    )
    {
        /** @var CreateRecoverTokenRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), CreateRecoverTokenRequest::class, 'json');
        $validator->validate($dto);

        $handler->handle(new CreateRecoverTokenCommand($dto->email));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}