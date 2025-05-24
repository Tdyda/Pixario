<?php

namespace App\Controller\Api\Account;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Validation\DtoValidator;
use App\Service\Validation\SignInRequest;
use App\Service\Validation\SignUpRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AccountController extends AbstractController
{
    #[Route('/account/sign-in', name: 'app_account_sign_in', methods: ['POST'])]
    public function signIn(
        Request                     $request,
        SerializerInterface         $serializer,
        DtoValidator                $validator,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        $dto = $serializer->deserialize($request->getContent(), SignInRequest::class, 'json');
        $validator->validate($dto);

        try {
            $user = $userRepository->findOneBy(['email' => $dto->email]);

            if (!$user || !$passwordHasher->isPasswordValid($user, $dto->password)) {
                return $this->json(['error' => 'Błędne dane logowania!'], 403);
            }

            return $this->json(['user' => $user], 201);
        } catch (\LogicException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/account/sign-up', name: 'app_account_sign_up', methods: ['POST'])]
    public function signUp(
        Request             $request,
        SerializerInterface $serializer,
        DtoValidator        $validator,
        UserRepository      $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    )
    {
        $dto = $serializer->deserialize($request->getContent(), SignUpRequest::class, 'json');
        $validator->validate($dto);

        try{
            $user = $userRepository->findOneBy(['email' => $dto->email]);
            if($user){
                return $this->json(['message' => "Użytkownik z tym adresem email już istnieje!"], 409);
            }

            $user = new User();
            $user->setEmail($dto->email);
            $user->setPassword($passwordHasher->hashPassword($user, $dto->password));

            $em->persist($user);
            $em->flush();

            return $this->json(['message' => 'Użytkownik został zarejestrowany.'], 201);
        }catch(\Exception $e){
            return $this->json(['error' => $e->getMessage()], 400);
        }

    }

}
