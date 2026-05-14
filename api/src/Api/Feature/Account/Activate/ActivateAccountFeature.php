<?php
namespace App\Api\Feature\Account\Activate;

namespace App\Api\Feature\Account\Activate;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Account\Activate\ActivateAccountCommand;
use App\Application\Feature\Account\Activate\ActivateAccountCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ActivateAccountFeature extends AbstractController
{
    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    #[Route('/api/activate-account', name: 'activate_account', methods: ['POST'])]
    public function activateAccount(
        Request $request,
        RequestValidator $validator,

        SerializerInterface $serializer,
        ActivateAccountCommandHandler $handler
    ): JsonResponse{
        /** @var ActivateAccountRequest $dto*/
        $dto = $serializer->deserialize($request->getContent(), ActivateAccountRequest::class, 'json');
        $validator->validate($dto);

        $handler->handle(new ActivateAccountCommand($dto->activationToken));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
